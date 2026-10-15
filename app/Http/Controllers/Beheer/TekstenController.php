<?php

namespace App\Http\Controllers\Beheer;

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

class TekstenController extends Controller
{
    public function index(): View
    {
        $talen = Language::active()->get();

        $paginas = CmsPage::with(['translations.language', 'sections.contents', 'editor'])
            ->whereIn('key', array_keys(config('cms.paginas')))
            ->orderBy('sort_order')
            ->get()
            ->map(function (CmsPage $page) use ($talen) {
                $secties = $this->secties(config("cms.paginas.{$page->key}.secties"));

                return [
                    'page' => $page,
                    'naam' => $page->translation($this->standaardtaal($talen)->code)?->title ?? $page->key,
                    'pad' => route(config("cms.paginas.{$page->key}.route"), absolute: false),
                    'aantal' => $this->aantalVelden($secties),
                    'ontbrekend' => $this->ontbrekend($secties, $this->paginaWaarden($page, $talen), $talen),
                ];
            });

        $algemeen = $this->secties(config('cms.algemeen'));

        return view('beheer.teksten.index', [
            'paginas' => $paginas,
            'algemeen' => [
                'aantal' => $this->aantalVelden($algemeen),
                'ontbrekend' => $this->ontbrekend($algemeen, $this->algemeneWaarden($talen), $talen),
            ],
        ]);
    }

    public function edit(CmsPage $page): View
    {
        $definitie = $this->definitie($page);
        $talen = Language::active()->get();

        $page->load(['translations.language', 'sections.contents', 'editor']);

        return view('beheer.teksten.pagina', [
            'page' => $page,
            'naam' => $page->translation($this->standaardtaal($talen)->code)?->title ?? $page->key,
            'url' => route($definitie['route']),
            'talen' => $talen,
            'secties' => $this->secties($definitie['secties']),
            'waarden' => $this->paginaWaarden($page, $talen),
        ]);
    }

    public function update(Request $request, CmsPage $page): RedirectResponse
    {
        $definitie = $this->definitie($page);
        $talen = Language::active()->get();
        $secties = $this->secties($definitie['secties']);

        $data = $request->validate([
            ...$this->regels($secties, $talen),
            ...$talen->flatMap(fn (Language $taal) => [
                "pagina.title.{$taal->code}" => ['required', 'string', 'max:150'],
                "pagina.meta_title.{$taal->code}" => ['nullable', 'string', 'max:70'],
                "pagina.meta_description.{$taal->code}" => ['nullable', 'string', 'max:160'],
            ])->all(),
        ], $this->meldingen());

        $page->load(['translations', 'sections.contents']);

        $gewijzigd = DB::transaction(function () use ($page, $secties, $talen, $data, $request) {
            $gewijzigd = false;

            foreach ($talen as $taal) {
                $vertaling = $page->translations->firstWhere('language_id', $taal->id)
                    ?? $page->translations()->make(['language_id' => $taal->id]);

                $vertaling->fill([
                    'title' => $data['pagina']['title'][$taal->code],
                    'meta_title' => $data['pagina']['meta_title'][$taal->code] ?? null,
                    'meta_description' => $data['pagina']['meta_description'][$taal->code] ?? null,
                ]);
                $vertaling->slug ??= Str::slug($vertaling->title);

                if ($vertaling->isDirty()) {
                    $gewijzigd = $vertaling->save() || $gewijzigd;
                }
            }

            foreach (array_keys($secties) as $volgorde => $sectieKey) {
                $sectie = $page->sections->firstWhere('key', $sectieKey)
                    ?? $page->sections()->create(['key' => $sectieKey, 'sort_order' => $volgorde + 1]);

                foreach (array_keys($this->velden($secties[$sectieKey])) as $veld) {
                    foreach ($talen as $taal) {
                        $tekst = $sectie->contents->first(fn (CmsContent $content) => $content->key === $veld && $content->language_id === $taal->id)
                            ?? $sectie->contents()->make(['key' => $veld, 'language_id' => $taal->id, 'type' => 'text']);

                        $opgeslagen = $this->bewaar($tekst, 'content', $data['teksten'][$sectieKey][$veld][$taal->code] ?? null, [
                            'updated_by' => $request->user()->id,
                        ]);

                        $gewijzigd = $opgeslagen || $gewijzigd;
                    }
                }
            }

            if ($gewijzigd) {
                $page->updated_by = $request->user()->id;
                $page->touch();
            }

            return $gewijzigd;
        });

        $naam = $page->translations->firstWhere('language_id', $this->standaardtaal($talen)->id)?->title;

        return redirect()->route('beheer.teksten.edit', $page)
            ->with('status', $gewijzigd ? "De teksten van {$naam} zijn opgeslagen." : 'Er was niets gewijzigd.');
    }

    public function editAlgemeen(): View
    {
        $talen = Language::active()->get();

        return view('beheer.teksten.algemeen', [
            'talen' => $talen,
            'secties' => $this->secties(config('cms.algemeen')),
            'waarden' => $this->algemeneWaarden($talen),
        ]);
    }

    public function updateAlgemeen(Request $request): RedirectResponse
    {
        $talen = Language::active()->get();
        $secties = $this->secties(config('cms.algemeen'));

        $data = $request->validate($this->regels($secties, $talen), $this->meldingen());

        $bestaand = CmsTranslation::whereIn('language_id', $talen->pluck('id'))
            ->get()
            ->keyBy(fn (CmsTranslation $vertaling) => "{$vertaling->language_id}:{$vertaling->key}");

        $gewijzigd = DB::transaction(function () use ($secties, $talen, $data, $bestaand) {
            $gewijzigd = false;

            foreach ($secties as $groep => $sectie) {
                foreach (array_keys($this->velden($sectie)) as $veld) {
                    foreach ($talen as $taal) {
                        $vertaling = $bestaand->get("{$taal->id}:{$groep}.{$veld}")
                            ?? new CmsTranslation(['language_id' => $taal->id, 'key' => "{$groep}.{$veld}"]);

                        $opgeslagen = $this->bewaar($vertaling, 'value', $data['teksten'][$groep][$veld][$taal->code] ?? null);

                        $gewijzigd = $opgeslagen || $gewijzigd;
                    }
                }
            }

            return $gewijzigd;
        });

        return redirect()->route('beheer.teksten.algemeen')
            ->with('status', $gewijzigd ? 'De algemene teksten zijn opgeslagen.' : 'Er was niets gewijzigd.');
    }

    /**
     * De definitie van een pagina uit config/cms.php.
     *
     * @return array{route: string, secties: array<string, array<string, mixed>>}
     */
    private function definitie(CmsPage $page): array
    {
        return config("cms.paginas.{$page->key}") ?? abort(404);
    }

    /**
     * Zet de secties uit de config om naar groepen met velden, zodat
     * herhalende items als losse groepen in het beheer staan.
     *
     * @param  array<string, array{naam: string, velden?: array<string, string|array{label: string, hint?: string}>, items?: array{naam: string, aantal: int, velden: array<string, string|array{label: string, hint?: string}>}}>  $definitie
     * @return array<string, array{naam: string, groepen: list<array{naam: ?string, velden: array<string, array{label: string, hint: ?string}>}>}>
     */
    private function secties(array $definitie): array
    {
        return collect($definitie)->map(function (array $sectie) {
            $groepen = [];

            if (isset($sectie['velden'])) {
                $groepen[] = ['naam' => null, 'velden' => $this->normaliseer($sectie['velden'])];
            }

            for ($nummer = 1; $nummer <= ($sectie['items']['aantal'] ?? 0); $nummer++) {
                $groepen[] = [
                    'naam' => "{$sectie['items']['naam']} {$nummer}",
                    'velden' => collect($this->normaliseer($sectie['items']['velden']))
                        ->mapWithKeys(fn (array $veld, string $key) => ["item_{$nummer}_{$key}" => $veld])
                        ->all(),
                ];
            }

            return ['naam' => $sectie['naam'], 'groepen' => $groepen];
        })->all();
    }

    /**
     * @param  array<string, string|array{label: string, hint?: string}>  $velden
     * @return array<string, array{label: string, hint: ?string}>
     */
    private function normaliseer(array $velden): array
    {
        return collect($velden)
            ->map(fn (string|array $veld) => is_array($veld) ? $veld + ['hint' => null] : ['label' => $veld, 'hint' => null])
            ->all();
    }

    /**
     * Alle velden van een sectie, over alle groepen heen.
     *
     * @param  array{groepen: list<array{velden: array<string, array{label: string, hint: ?string}>}>}  $sectie
     * @return array<string, array{label: string, hint: ?string}>
     */
    private function velden(array $sectie): array
    {
        return collect($sectie['groepen'])->flatMap(fn (array $groep) => $groep['velden'])->all();
    }

    /**
     * @param  array<string, array<string, mixed>>  $secties
     */
    private function aantalVelden(array $secties): int
    {
        return collect($secties)->sum(fn (array $sectie) => count($this->velden($sectie)));
    }

    /**
     * Het aantal teksten dat in een van de talen nog niet is ingevuld.
     *
     * @param  array<string, array<string, mixed>>  $secties
     * @param  array<string, array<string, array<string, string>>>  $waarden
     * @param  Collection<int, Language>  $talen
     */
    private function ontbrekend(array $secties, array $waarden, Collection $talen): int
    {
        return collect($secties)
            ->map(fn (array $sectie, string $sectieKey) => collect($this->velden($sectie))
                ->keys()
                ->sum(fn (string $veld) => $talen->filter(fn (Language $taal) => blank($waarden[$sectieKey][$veld][$taal->code] ?? null))->count()))
            ->sum();
    }

    /**
     * @param  Collection<int, Language>  $talen
     * @return array<string, array<string, array<string, string>>>
     */
    private function paginaWaarden(CmsPage $page, Collection $talen): array
    {
        $codes = $talen->pluck('code', 'id');
        $waarden = [];

        foreach ($page->sections as $sectie) {
            foreach ($sectie->contents as $tekst) {
                if ($codes->has($tekst->language_id)) {
                    $waarden[$sectie->key][$tekst->key][$codes[$tekst->language_id]] = $tekst->content;
                }
            }
        }

        return $waarden;
    }

    /**
     * @param  Collection<int, Language>  $talen
     * @return array<string, array<string, array<string, string>>>
     */
    private function algemeneWaarden(Collection $talen): array
    {
        $codes = $talen->pluck('code', 'id');
        $waarden = [];

        foreach (CmsTranslation::whereIn('language_id', $codes->keys())->get() as $vertaling) {
            [$groep, $veld] = explode('.', $vertaling->key, 2);

            $waarden[$groep][$veld][$codes[$vertaling->language_id]] = $vertaling->value;
        }

        return $waarden;
    }

    /**
     * Sla een tekst op, of verwijder hem als het veld leeg is gelaten.
     * Geeft terug of er iets in de database is veranderd.
     *
     * @param  array<string, mixed>  $extra  Kolommen die alleen bij een wijziging worden bijgewerkt.
     */
    private function bewaar(Model $tekst, string $kolom, ?string $waarde, array $extra = []): bool
    {
        if ($waarde === null) {
            return $tekst->exists && $tekst->delete();
        }

        $tekst->{$kolom} = $waarde;

        if (! $tekst->isDirty()) {
            return false;
        }

        return $tekst->forceFill($extra)->save();
    }

    /**
     * Elke tekst is verplicht in de standaardtaal; in de andere talen valt
     * een leeg veld terug op de standaardtaal.
     *
     * @param  array<string, array<string, mixed>>  $secties
     * @param  Collection<int, Language>  $talen
     * @return array<string, list<string>>
     */
    private function regels(array $secties, Collection $talen): array
    {
        $regels = [];

        foreach ($secties as $sectieKey => $sectie) {
            foreach (array_keys($this->velden($sectie)) as $veld) {
                foreach ($talen as $taal) {
                    $regels["teksten.{$sectieKey}.{$veld}.{$taal->code}"] = [$taal->is_default ? 'required' : 'nullable', 'string', 'max:5000'];
                }
            }
        }

        return $regels;
    }

    /**
     * @return array<string, string>
     */
    private function meldingen(): array
    {
        return [
            'required' => 'Dit veld mag niet leeg zijn.',
            'string' => 'Vul hier een tekst in.',
            'max' => 'Deze tekst is te lang (maximaal :max tekens).',
        ];
    }

    /**
     * @param  Collection<int, Language>  $talen
     */
    private function standaardtaal(Collection $talen): Language
    {
        return $talen->firstWhere('is_default', true) ?? $talen->first();
    }
}
