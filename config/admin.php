<?php

/*
|--------------------------------------------------------------------------
| Admin area settings
|--------------------------------------------------------------------------
|
| The sidebar and the shortcuts on the dashboard are built from the
| navigation. Add a new section by adding an item with an existing route
| name here.
|
| The facilities of the accommodation can be given one of the icons below.
| The key is the name of the icon in <x-site.icon>.
|
*/

return [

    'navigation' => [
        [
            'title' => 'Overzicht',
            'items' => [
                [
                    'route' => 'admin.dashboard',
                    'label' => 'Dashboard',
                    'icon' => 'dashboard',
                    'description' => 'Een overzicht van aanvragen, aankomsten en de bezetting.',
                ],
            ],
        ],
        [
            'title' => 'Verhuur',
            'items' => [
                [
                    'route' => 'admin.reservations',
                    'label' => 'Reserveringen',
                    'icon' => 'inbox',
                    'description' => 'Aanvragen beoordelen en reserveringen beheren.',
                ],
                [
                    'route' => 'admin.availability',
                    'label' => 'Beschikbaarheid',
                    'icon' => 'calendar',
                    'description' => 'Periodes blokkeren en de kalender bijhouden.',
                ],
                [
                    'route' => 'admin.rates',
                    'label' => 'Tarieven',
                    'icon' => 'euro',
                    'description' => 'Seizoensprijzen, bijkomende kosten en kortingen.',
                ],
            ],
        ],
        [
            'title' => 'Website',
            'items' => [
                [
                    'route' => 'admin.photos',
                    'label' => 'Foto\'s',
                    'icon' => 'photo',
                    'description' => 'Foto\'s uploaden en de fotogalerij indelen.',
                ],
                [
                    'route' => 'admin.texts',
                    'label' => 'Teksten',
                    'icon' => 'document',
                    'description' => 'De teksten op de pagina\'s van de website aanpassen.',
                ],
                [
                    'route' => 'admin.accommodation',
                    'label' => 'Woninggegevens',
                    'icon' => 'home',
                    'description' => 'Kenmerken, voorzieningen en adres van de woning.',
                ],
            ],
        ],
    ],

    'facility_icons' => [
        'wifi' => 'Wifi',
        'pool' => 'Zwembad',
        'snowflake' => 'Airconditioning',
        'tv' => 'Televisie',
        'kitchen' => 'Keuken',
        'dishwasher' => 'Vaatwasser',
        'washing' => 'Wasmachine',
        'bbq' => 'Barbecue',
        'parking' => 'Parkeerplaats',
        'crib' => 'Kinderbed',
        'bed' => 'Bed',
        'bath' => 'Bad of douche',
        'sun' => 'Terras',
        'palm' => 'Tuin',
        'umbrella' => 'Parasol',
        'utensils' => 'Servies en bestek',
        'water' => 'Water',
        'shield' => 'Kluis of beveiliging',
        'key' => 'Sleutel',
        'car' => 'Auto',
        'paw' => 'Huisdieren',
        'check' => 'Vinkje',
    ],

];
