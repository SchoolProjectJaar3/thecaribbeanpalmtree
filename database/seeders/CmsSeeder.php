<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use App\Models\Language;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            'nl' => Language::where('code', 'nl')->firstOrFail(),
            'en' => Language::where('code', 'en')->firstOrFail(),
        ];

        $page = CmsPage::updateOrCreate(['key' => 'home'], [
            'template' => 'home',
            'is_published' => true,
            'show_in_menu' => true,
            'sort_order' => 1,
        ]);

        foreach ($languages as $language) {
            $page->translations()->updateOrCreate(
                ['language_id' => $language->id],
                ['title' => 'Home', 'slug' => 'home'],
            );
        }

        $content = [
            'hero' => [
                'title' => [
                    'nl' => 'The Caribbean Palm Tree',
                    'en' => 'The Caribbean Palm Tree',
                ],
                'description' => [
                    'nl' => 'Geniet van een luxe vakantiewoning op Curaçao, rustig en centraal gelegen op Harmonie, dichtbij de mooiste stranden van het eiland.',
                    'en' => 'Enjoy a luxury holiday home on Curaçao, quiet and centrally located in Harmonie, close to the most beautiful beaches on the island.',
                ],
                'button_text' => [
                    'nl' => 'Bekijk beschikbaarheid',
                    'en' => 'Check availability',
                ],
            ],
            'house' => [
                'text' => [
                    'nl' => 'Een ruime, lichte woning met een eigen zwembad en een tuin vol tropisch groen.',
                    'en' => 'A spacious, bright home with its own pool and a garden full of tropical greenery.',
                ],
            ],
            'surroundings' => [
                'text' => [
                    'nl' => 'Vanaf Harmonie ben je snel bij de mooiste stranden, in Willemstad en op de luchthaven.',
                    'en' => 'From Harmonie you are quickly at the most beautiful beaches, in Willemstad and at the airport.',
                ],
            ],
        ];

        $order = 1;

        foreach ($content as $sectionKey => $fields) {
            $section = $page->sections()->updateOrCreate(
                ['key' => $sectionKey],
                ['sort_order' => $order++, 'is_visible' => true],
            );

            foreach ($fields as $fieldKey => $texts) {
                foreach ($texts as $code => $text) {
                    $section->contents()->firstOrCreate(
                        ['language_id' => $languages[$code]->id, 'key' => $fieldKey],
                        ['type' => 'text', 'content' => $text],
                    );
                }
            }
        }
    }
}
