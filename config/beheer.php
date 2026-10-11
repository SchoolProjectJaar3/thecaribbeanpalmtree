<?php

/*
|--------------------------------------------------------------------------
| Navigatie van de beheeromgeving
|--------------------------------------------------------------------------
|
| De sidebar en de snelkoppelingen op het dashboard worden opgebouwd uit
| deze lijst. Een nieuw onderdeel voeg je toe door hier een item met een
| bestaande routenaam op te nemen.
|
*/

return [

    'navigatie' => [
        [
            'titel' => 'Overzicht',
            'items' => [
                [
                    'route' => 'beheer.dashboard',
                    'label' => 'Dashboard',
                    'icoon' => 'dashboard',
                    'omschrijving' => 'Een overzicht van aanvragen, aankomsten en de bezetting.',
                ],
            ],
        ],
        [
            'titel' => 'Verhuur',
            'items' => [
                [
                    'route' => 'beheer.reserveringen',
                    'label' => 'Reserveringen',
                    'icoon' => 'inbox',
                    'omschrijving' => 'Aanvragen beoordelen en reserveringen beheren.',
                ],
                [
                    'route' => 'beheer.beschikbaarheid',
                    'label' => 'Beschikbaarheid',
                    'icoon' => 'calendar',
                    'omschrijving' => 'Periodes blokkeren en de kalender bijhouden.',
                ],
                [
                    'route' => 'beheer.tarieven',
                    'label' => 'Tarieven',
                    'icoon' => 'euro',
                    'omschrijving' => 'Seizoensprijzen, bijkomende kosten en kortingen.',
                ],
            ],
        ],
        [
            'titel' => 'Website',
            'items' => [
                [
                    'route' => 'beheer.fotos',
                    'label' => 'Foto\'s',
                    'icoon' => 'photo',
                    'omschrijving' => 'Foto\'s uploaden en de fotogalerij indelen.',
                ],
                [
                    'route' => 'beheer.teksten',
                    'label' => 'Teksten',
                    'icoon' => 'document',
                    'omschrijving' => 'De teksten op de pagina\'s van de website aanpassen.',
                ],
                [
                    'route' => 'beheer.woning',
                    'label' => 'Woninggegevens',
                    'icoon' => 'home',
                    'omschrijving' => 'Kenmerken, voorzieningen en adres van de woning.',
                ],
            ],
        ],
    ],

];
