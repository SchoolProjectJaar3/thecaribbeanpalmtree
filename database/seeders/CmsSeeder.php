<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use App\Models\CmsTranslation;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CmsSeeder extends Seeder
{
    /**
     * Vul de pagina's, secties en teksten uit config/cms.php met de teksten
     * in database/seeders/cms. Bestaande teksten worden niet overschreven,
     * zodat aanpassingen uit het beheer behouden blijven.
     */
    public function run(): void
    {
        $languages = Language::all()->keyBy('code');

        foreach (array_keys(config('cms.paginas')) as $index => $key) {
            $this->seedPage($key, $index + 1, require __DIR__."/cms/{$key}.php", $languages);
        }

        $this->seedTranslations(require __DIR__.'/cms/algemeen.php', $languages);
    }

    /**
     * @param  array{show_in_menu: bool, translations: array<string, array<string, string>>, sections: array<string, array<string, mixed>>}  $data
     * @param  Collection<string, Language>  $languages
     */
    private function seedPage(string $key, int $sortOrder, array $data, Collection $languages): void
    {
        $page = CmsPage::updateOrCreate(['key' => $key], [
            'template' => $key,
            'is_published' => true,
            'show_in_menu' => $data['show_in_menu'],
            'sort_order' => $sortOrder,
        ]);

        foreach ($data['translations'] as $code => $translation) {
            $page->translations()->firstOrCreate(['language_id' => $languages[$code]->id], $translation);
        }

        foreach (array_keys(config("cms.paginas.{$key}.secties")) as $index => $sectionKey) {
            $section = $page->sections()->updateOrCreate(
                ['key' => $sectionKey],
                ['sort_order' => $index + 1, 'is_visible' => true],
            );

            foreach ($this->fields($data['sections'][$sectionKey] ?? []) as $fieldKey => $texts) {
                foreach ($texts as $code => $text) {
                    $section->contents()->firstOrCreate(
                        ['language_id' => $languages[$code]->id, 'key' => $fieldKey],
                        ['type' => 'text', 'content' => $text],
                    );
                }
            }
        }
    }

    /**
     * @param  array<string, array<string, array<string, string>>>  $groups
     * @param  Collection<string, Language>  $languages
     */
    private function seedTranslations(array $groups, Collection $languages): void
    {
        foreach ($groups as $group => $fields) {
            foreach ($fields as $fieldKey => $texts) {
                foreach ($texts as $code => $text) {
                    CmsTranslation::firstOrCreate(
                        ['language_id' => $languages[$code]->id, 'key' => "{$group}.{$fieldKey}"],
                        ['value' => $text],
                    );
                }
            }
        }
    }

    /**
     * Zet herhalende items om naar velden met de sleutel item_{nummer}_{veld}.
     *
     * @param  array<string, mixed>  $section
     * @return array<string, array<string, string>>
     */
    private function fields(array $section): array
    {
        foreach (Arr::pull($section, 'items', []) as $index => $item) {
            foreach ($item as $fieldKey => $texts) {
                $section['item_'.($index + 1).'_'.$fieldKey] = $texts;
            }
        }

        return $section;
    }
}
