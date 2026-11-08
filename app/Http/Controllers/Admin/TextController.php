<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsContent;
use App\Models\CmsPage;
use App\Models\CmsTranslation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TextController extends Controller
{
    public function index(): View
    {
        $languages = Language::active()->get();

        $pages = CmsPage::with(['translations.language', 'sections.contents', 'editor'])
            ->whereIn('key', array_keys(config('cms.pages')))
            ->orderBy('sort_order')
            ->get()
            ->map(function (CmsPage $page) use ($languages) {
                $sections = $this->sections(config("cms.pages.{$page->key}.sections"));

                return [
                    'page' => $page,
                    'name' => $page->translation($this->defaultLanguage($languages)->code)?->title ?? $page->key,
                    'path' => route(config("cms.pages.{$page->key}.route"), absolute: false),
                    'count' => $this->fieldCount($sections),
                    'missing' => $this->missing($sections, $this->pageValues($page, $languages), $languages),
                ];
            });

        $general = $this->sections(config('cms.general'));

        return view('admin.texts.index', [
            'pages' => $pages,
            'general' => [
                'count' => $this->fieldCount($general),
                'missing' => $this->missing($general, $this->generalValues($languages), $languages),
            ],
        ]);
    }

    public function edit(CmsPage $page): View
    {
        $definition = $this->definition($page);
        $languages = Language::active()->get();

        $page->load(['translations.language', 'sections.contents', 'editor']);

        return view('admin.texts.page', [
            'page' => $page,
            'name' => $page->translation($this->defaultLanguage($languages)->code)?->title ?? $page->key,
            'url' => route($definition['route']),
            'languages' => $languages,
            'sections' => $this->sections($definition['sections']),
            'values' => $this->pageValues($page, $languages),
        ]);
    }

    public function update(Request $request, CmsPage $page): RedirectResponse
    {
        $definition = $this->definition($page);
        $languages = Language::active()->get();
        $sections = $this->sections($definition['sections']);

        $data = $request->validate([
            ...$this->rules($sections, $languages),
            ...$languages->flatMap(fn (Language $language) => [
                "page.title.{$language->code}" => ['required', 'string', 'max:150'],
                "page.meta_title.{$language->code}" => ['nullable', 'string', 'max:70'],
                "page.meta_description.{$language->code}" => ['nullable', 'string', 'max:160'],
            ])->all(),
        ], $this->messages());

        $page->load(['translations', 'sections.contents']);

        $changed = DB::transaction(function () use ($page, $sections, $languages, $data, $request) {
            $changed = false;

            foreach ($languages as $language) {
                $translation = $page->translations->firstWhere('language_id', $language->id)
                    ?? $page->translations()->make(['language_id' => $language->id]);

                $translation->fill([
                    'title' => $data['page']['title'][$language->code],
                    'meta_title' => $data['page']['meta_title'][$language->code] ?? null,
                    'meta_description' => $data['page']['meta_description'][$language->code] ?? null,
                ]);
                $translation->slug ??= Str::slug($translation->title);

                if ($translation->isDirty()) {
                    $changed = $translation->save() || $changed;
                }
            }

            foreach (array_keys($sections) as $order => $sectionKey) {
                $section = $page->sections->firstWhere('key', $sectionKey)
                    ?? $page->sections()->create(['key' => $sectionKey, 'sort_order' => $order + 1]);

                foreach (array_keys($this->fields($sections[$sectionKey])) as $field) {
                    foreach ($languages as $language) {
                        $text = $section->contents->first(fn (CmsContent $content) => $content->key === $field && $content->language_id === $language->id)
                            ?? $section->contents()->make(['key' => $field, 'language_id' => $language->id, 'type' => 'text']);

                        $saved = $this->save($text, 'content', $data['texts'][$sectionKey][$field][$language->code] ?? null, [
                            'updated_by' => $request->user()->id,
                        ]);

                        $changed = $saved || $changed;
                    }
                }
            }

            if ($changed) {
                $page->updated_by = $request->user()->id;
                $page->touch();
            }

            return $changed;
        });

        $name = $page->translations->firstWhere('language_id', $this->defaultLanguage($languages)->id)?->title;

        return redirect()->route('admin.texts.edit', $page)
            ->with('status', $changed ? "De teksten van {$name} zijn opgeslagen." : 'Er was niets gewijzigd.');
    }

    public function editGeneral(): View
    {
        $languages = Language::active()->get();

        return view('admin.texts.general', [
            'languages' => $languages,
            'sections' => $this->sections(config('cms.general')),
            'values' => $this->generalValues($languages),
        ]);
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $languages = Language::active()->get();
        $sections = $this->sections(config('cms.general'));

        $data = $request->validate($this->rules($sections, $languages), $this->messages());

        $existing = CmsTranslation::whereIn('language_id', $languages->pluck('id'))
            ->get()
            ->keyBy(fn (CmsTranslation $translation) => "{$translation->language_id}:{$translation->key}");

        $changed = DB::transaction(function () use ($sections, $languages, $data, $existing) {
            $changed = false;

            foreach ($sections as $group => $section) {
                foreach (array_keys($this->fields($section)) as $field) {
                    foreach ($languages as $language) {
                        $translation = $existing->get("{$language->id}:{$group}.{$field}")
                            ?? new CmsTranslation(['language_id' => $language->id, 'key' => "{$group}.{$field}"]);

                        $saved = $this->save($translation, 'value', $data['texts'][$group][$field][$language->code] ?? null);

                        $changed = $saved || $changed;
                    }
                }
            }

            return $changed;
        });

        return redirect()->route('admin.texts.general')
            ->with('status', $changed ? 'De algemene teksten zijn opgeslagen.' : 'Er was niets gewijzigd.');
    }

    /**
     * The definition of a page from config/cms.php.
     *
     * @return array{route: string, sections: array<string, array<string, mixed>>}
     */
    private function definition(CmsPage $page): array
    {
        return config("cms.pages.{$page->key}") ?? abort(404);
    }

    /**
     * Convert the sections from the config into groups of fields, so that
     * repeating items appear as separate groups in the admin area.
     *
     * @param  array<string, array{name: string, fields?: array<string, string|array{label: string, hint?: string}>, items?: array{name: string, count: int, fields: array<string, string|array{label: string, hint?: string}>}}>  $definition
     * @return array<string, array{name: string, groups: list<array{name: ?string, fields: array<string, array{label: string, hint: ?string}>}>}>
     */
    private function sections(array $definition): array
    {
        return collect($definition)->map(function (array $section) {
            $groups = [];

            if (isset($section['fields'])) {
                $groups[] = ['name' => null, 'fields' => $this->normalize($section['fields'])];
            }

            for ($number = 1; $number <= ($section['items']['count'] ?? 0); $number++) {
                $groups[] = [
                    'name' => "{$section['items']['name']} {$number}",
                    'fields' => collect($this->normalize($section['items']['fields']))
                        ->mapWithKeys(fn (array $field, string $key) => ["item_{$number}_{$key}" => $field])
                        ->all(),
                ];
            }

            return ['name' => $section['name'], 'groups' => $groups];
        })->all();
    }

    /**
     * @param  array<string, string|array{label: string, hint?: string}>  $fields
     * @return array<string, array{label: string, hint: ?string}>
     */
    private function normalize(array $fields): array
    {
        return collect($fields)
            ->map(fn (string|array $field) => is_array($field) ? $field + ['hint' => null] : ['label' => $field, 'hint' => null])
            ->all();
    }

    /**
     * All fields of a section, across all groups.
     *
     * @param  array{groups: list<array{fields: array<string, array{label: string, hint: ?string}>}>}  $section
     * @return array<string, array{label: string, hint: ?string}>
     */
    private function fields(array $section): array
    {
        return collect($section['groups'])->flatMap(fn (array $group) => $group['fields'])->all();
    }

    /**
     * @param  array<string, array<string, mixed>>  $sections
     */
    private function fieldCount(array $sections): int
    {
        return collect($sections)->sum(fn (array $section) => count($this->fields($section)));
    }

    /**
     * The number of texts that have not yet been filled in for one of the languages.
     *
     * @param  array<string, array<string, mixed>>  $sections
     * @param  array<string, array<string, array<string, string>>>  $values
     * @param  Collection<int, Language>  $languages
     */
    private function missing(array $sections, array $values, Collection $languages): int
    {
        return collect($sections)
            ->map(fn (array $section, string $sectionKey) => collect($this->fields($section))
                ->keys()
                ->sum(fn (string $field) => $languages->filter(fn (Language $language) => blank($values[$sectionKey][$field][$language->code] ?? null))->count()))
            ->sum();
    }

    /**
     * @param  Collection<int, Language>  $languages
     * @return array<string, array<string, array<string, string>>>
     */
    private function pageValues(CmsPage $page, Collection $languages): array
    {
        $codes = $languages->pluck('code', 'id');
        $values = [];

        foreach ($page->sections as $section) {
            foreach ($section->contents as $text) {
                if ($codes->has($text->language_id)) {
                    $values[$section->key][$text->key][$codes[$text->language_id]] = $text->content;
                }
            }
        }

        return $values;
    }

    /**
     * @param  Collection<int, Language>  $languages
     * @return array<string, array<string, array<string, string>>>
     */
    private function generalValues(Collection $languages): array
    {
        $codes = $languages->pluck('code', 'id');
        $values = [];

        foreach (CmsTranslation::whereIn('language_id', $codes->keys())->get() as $translation) {
            [$group, $field] = explode('.', $translation->key, 2);

            $values[$group][$field][$codes[$translation->language_id]] = $translation->value;
        }

        return $values;
    }

    /**
     * Save a text, or delete it when the field was left empty.
     * Returns whether anything changed in the database.
     *
     * @param  array<string, mixed>  $extra  Columns that are only updated when something changed.
     */
    private function save(Model $text, string $column, ?string $value, array $extra = []): bool
    {
        if ($value === null) {
            return $text->exists && $text->delete();
        }

        $text->{$column} = $value;

        if (! $text->isDirty()) {
            return false;
        }

        return $text->forceFill($extra)->save();
    }

    /**
     * Every text is required in the default language; in the other languages
     * an empty field falls back to the default language.
     *
     * @param  array<string, array<string, mixed>>  $sections
     * @param  Collection<int, Language>  $languages
     * @return array<string, list<string>>
     */
    private function rules(array $sections, Collection $languages): array
    {
        $rules = [];

        foreach ($sections as $sectionKey => $section) {
            foreach (array_keys($this->fields($section)) as $field) {
                foreach ($languages as $language) {
                    $rules["texts.{$sectionKey}.{$field}.{$language->code}"] = [$language->is_default ? 'required' : 'nullable', 'string', 'max:5000'];
                }
            }
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'required' => 'Dit veld mag niet leeg zijn.',
            'string' => 'Vul hier een tekst in.',
            'max' => 'Deze tekst is te lang (maximaal :max tekens).',
        ];
    }

    /**
     * @param  Collection<int, Language>  $languages
     */
    private function defaultLanguage(Collection $languages): Language
    {
        return $languages->firstWhere('is_default', true) ?? $languages->first();
    }
}
