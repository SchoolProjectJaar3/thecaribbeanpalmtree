<?php

/*
|--------------------------------------------------------------------------
| Teksten van de website
|--------------------------------------------------------------------------
|
| Welke teksten de eigenaar per pagina kan aanpassen. De sleutels van
| pagina's, secties en velden zijn gelijk aan die in de tabellen
| cms_pages, cms_sections en cms_contents. De teksten zelf staan in de
| database en worden gevuld door de CmsSeeder.
|
| Een veld is een label, of een array met een label en een uitleg (hint).
| Velden met de sleutel 'intro' of 'text', of die eindigen op '_intro' of
| '_text', krijgen in het beheer een groot tekstvak.
|
| Herhalende onderdelen, zoals activiteiten, staan onder 'items'. Elk item
| krijgt de sleutel item_{nummer}_{veld}, bijvoorbeeld item_1_title.
|
| De 'algemeen'-teksten staan op elke pagina en worden opgeslagen in
| cms_translations, met als sleutel {groep}.{veld}, bijvoorbeeld footer.copyright.
|
*/

$hero = [
    'naam' => 'Bovenaan de pagina',
    'velden' => [
        'eyebrow' => 'Bovenschrift',
        'title' => 'Titel',
        'intro' => 'Introductie',
    ],
];

$cta = [
    'naam' => 'Contactblok onderaan',
    'velden' => [
        'title' => 'Titel',
    ],
];

$prijsindicatie = [
    'night' => 'Eén nacht',
    'nights' => 'Meerdere nachten',
    'discount' => 'Korting',
    'cleaning' => 'Schoonmaakkosten',
    'tourist_tax' => 'Toeristenbelasting',
    'total' => 'Totaal',
];

return [

    'paginas' => [

        'home' => [
            'route' => 'home',
            'secties' => [
                'hero' => [
                    'naam' => 'Bovenaan de pagina',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Beschrijving',
                        'button' => 'Knop beschikbaarheid',
                        'booking_button' => 'Knop boeken',
                    ],
                ],
                'photos' => [
                    'naam' => 'Sfeerimpressie',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar de foto\'s',
                    ],
                ],
                'property' => [
                    'naam' => 'Het huis',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => 'Korte tekst over het huis',
                        'link' => 'Link naar de voorzieningen',
                    ],
                ],
                'surroundings' => [
                    'naam' => 'Omgeving',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => 'Tekst over de omgeving',
                        'link' => 'Link naar de omgeving',
                    ],
                    'items' => [
                        'naam' => 'Plek',
                        'aantal' => 4,
                        'velden' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                            'travel_time' => 'Reistijd',
                        ],
                    ],
                ],
                'availability' => [
                    'naam' => 'Beschikbaarheid',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => 'Tekst',
                        'button' => 'Knop',
                    ],
                ],
                'reviews' => [
                    'naam' => 'Reviews',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar alle reviews',
                    ],
                ],
                'faq' => [
                    'naam' => 'Veelgestelde vragen',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar alle vragen',
                    ],
                ],
                'owner' => [
                    'naam' => 'Over de eigenaar',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => 'Tekst',
                        'link' => 'Link naar het verhaal',
                    ],
                ],
                'closing' => [
                    'naam' => 'Afsluiting',
                    'velden' => [
                        'title' => 'Titel',
                        'text' => 'Tekst',
                        'button' => 'Knop beschikbaarheid',
                        'whatsapp_button' => 'Knop WhatsApp',
                        'email_label' => 'Tekst vóór het e-mailadres',
                    ],
                ],
            ],
        ],

        'property' => [
            'route' => 'het-huis',
            'secties' => [
                'hero' => $hero,
                'description' => [
                    'naam' => 'Over de woning',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => ['label' => 'Tekst', 'hint' => 'Een lege regel begint een nieuwe alinea.'],
                        'button' => 'Knop',
                    ],
                ],
                'amenities' => [
                    'naam' => 'Voorzieningen',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                    ],
                ],
                'rooms' => [
                    'naam' => 'Indeling',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar de foto\'s',
                    ],
                    'items' => [
                        'naam' => 'Ruimte',
                        'aantal' => 4,
                        'velden' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
            ],
        ],

        'photos' => [
            'route' => 'fotos',
            'secties' => [
                'hero' => $hero,
                'gallery' => [
                    'naam' => 'Fotogalerij',
                    'velden' => [
                        'all_photos' => 'Filter voor alle foto\'s',
                        'text' => 'Opmerking onder de foto\'s',
                    ],
                ],
            ],
        ],

        'availability' => [
            'route' => 'beschikbaarheid',
            'secties' => [
                'hero' => $hero,
                'calendar' => [
                    'naam' => 'Kalender',
                    'velden' => [
                        'selected' => 'Legenda: jouw keuze',
                        'unavailable_message' => 'Melding bij bezette dagen',
                    ],
                ],
                'summary' => [
                    'naam' => 'Jouw verblijf',
                    'velden' => [
                        'title' => 'Titel',
                        'arrival' => 'Aankomst',
                        'arrival_placeholder' => 'Aankomst nog niet gekozen',
                        'departure' => 'Vertrek',
                        'departure_placeholder' => 'Vertrek nog niet gekozen',
                        'estimate' => 'Indicatie verblijf',
                        'nights' => ['label' => 'Aantal nachten', 'hint' => ':aantal wordt het aantal nachten.'],
                        'estimate_note' => 'Toelichting bij de indicatie',
                        'rates_link' => 'Link naar de tarieven',
                        'minimum_stay' => ['label' => 'Melding minimale verblijfsduur', 'hint' => ':aantal wordt het minimale aantal nachten.'],
                        'button' => 'Knop',
                        'reset' => 'Keuze wissen',
                    ],
                ],
                'minimum_stay' => [
                    'naam' => 'Minimale verblijfsduur',
                    'velden' => [
                        'title' => 'Titel',
                        'low_season' => 'Laagseizoen',
                        'high_season' => 'Hoogseizoen',
                        'nights' => ['label' => 'Aantal nachten', 'hint' => ':aantal wordt het minimale aantal nachten.'],
                        'text' => 'Tekst',
                    ],
                ],
                'cta' => [
                    'naam' => 'Contactblok onderaan',
                    'velden' => [
                        'title' => 'Titel',
                        'text' => 'Tekst',
                    ],
                ],
            ],
        ],

        'rates' => [
            'route' => 'tarieven',
            'secties' => [
                'hero' => $hero,
                'seasons' => [
                    'naam' => 'Prijs per nacht',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'low_title' => 'Laagseizoen',
                        'low_period' => 'Periode laagseizoen',
                        'high_title' => 'Hoogseizoen',
                        'high_period' => 'Periode hoogseizoen',
                        'per_night' => 'Tekst achter de prijs',
                        'minimum_nights' => ['label' => 'Minimaal aantal nachten', 'hint' => ':aantal wordt het minimale aantal nachten.'],
                    ],
                ],
                'costs' => [
                    'naam' => 'Bijkomende kosten',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'cleaning' => 'Schoonmaakkosten',
                        'cleaning_note' => 'Toelichting schoonmaakkosten',
                        'tourist_tax' => 'Toeristenbelasting',
                        'tourist_tax_note' => 'Toelichting toeristenbelasting',
                        'deposit' => 'Borg',
                        'deposit_note' => 'Toelichting borg',
                    ],
                ],
                'discounts' => [
                    'naam' => 'Kortingen',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'from_nights' => ['label' => 'Vanaf aantal nachten', 'hint' => ':aantal wordt het aantal nachten.'],
                        'percentage' => ['label' => 'Korting', 'hint' => ':procent wordt het kortingspercentage.'],
                        'text' => 'Toelichting',
                    ],
                ],
                'calculator' => [
                    'naam' => 'Prijsindicatie',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                        'arrival' => 'Aankomst',
                        'departure' => 'Vertrek',
                        'guests' => 'Aantal personen',
                        'person' => 'Eén persoon',
                        'persons' => 'Meerdere personen',
                        'invalid_dates' => 'Melding bij ongeldige data',
                    ],
                ],
                'estimate' => [
                    'naam' => 'Jouw indicatie',
                    'velden' => [
                        'title' => 'Titel',
                        'empty' => 'Tekst zonder gekozen data',
                        ...$prijsindicatie,
                        'deposit_note' => ['label' => 'Toelichting borg', 'hint' => ':bedrag wordt het bedrag van de borg.'],
                        'minimum_stay' => ['label' => 'Melding minimale verblijfsduur', 'hint' => ':aantal wordt het minimale aantal nachten.'],
                        'button' => 'Knop',
                    ],
                ],
            ],
        ],

        'surroundings' => [
            'route' => 'omgeving',
            'secties' => [
                'hero' => $hero,
                'distances' => [
                    'naam' => 'Afstanden',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                        'destination' => 'Kolom bestemming',
                        'travel_time' => 'Kolom reistijd',
                        'distance' => 'Kolom afstand',
                    ],
                ],
                'beaches' => [
                    'naam' => 'Stranden',
                    'velden' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'naam' => 'Strand',
                        'aantal' => 9,
                        'velden' => [
                            'name' => 'Naam',
                            'travel_time' => 'Reistijd',
                            'distance' => 'Afstand',
                        ],
                    ],
                ],
                'amenities' => [
                    'naam' => 'Voorzieningen en steden',
                    'velden' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'naam' => 'Bestemming',
                        'aantal' => 3,
                        'velden' => [
                            'name' => 'Naam',
                            'travel_time' => 'Reistijd',
                            'distance' => 'Afstand',
                        ],
                    ],
                ],
                'restaurants' => [
                    'naam' => 'Restaurants',
                    'velden' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'naam' => 'Restaurant',
                        'aantal' => 2,
                        'velden' => [
                            'name' => 'Naam',
                            'travel_time' => 'Reistijd',
                            'distance' => 'Afstand',
                        ],
                    ],
                ],
                'more_beaches' => [
                    'naam' => 'Meer stranden',
                    'velden' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'naam' => 'Strand',
                        'aantal' => 4,
                        'velden' => [
                            'name' => 'Naam',
                        ],
                    ],
                ],
                'map' => [
                    'naam' => 'Kaart',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar Google Maps',
                    ],
                ],
                'activities' => [
                    'naam' => 'Activiteiten',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                    ],
                    'items' => [
                        'naam' => 'Activiteit',
                        'aantal' => 8,
                        'velden' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
            ],
        ],

        'reviews' => [
            'route' => 'reviews',
            'secties' => [
                'hero' => $hero,
                'summary' => [
                    'naam' => 'Samenvatting',
                    'velden' => [
                        'based_on' => ['label' => 'Aantal reviews', 'hint' => ':aantal wordt het aantal reviews.'],
                        'star' => 'Eén ster',
                        'stars' => 'Meerdere sterren',
                    ],
                ],
                'cta' => $cta,
            ],
        ],

        'faq' => [
            'route' => 'faq',
            'secties' => [
                'hero' => $hero,
                'contact' => [
                    'naam' => 'Vraag niet gevonden',
                    'velden' => [
                        'title' => 'Titel',
                        'text' => 'Tekst',
                        'button' => 'Knop',
                    ],
                ],
            ],
        ],

        'owner' => [
            'route' => 'over-de-eigenaar',
            'secties' => [
                'hero' => [
                    'naam' => 'Bovenaan de pagina',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                    ],
                ],
                'story' => [
                    'naam' => 'Verhaal',
                    'items' => [
                        'naam' => 'Blok',
                        'aantal' => 2,
                        'velden' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
                'quote' => [
                    'naam' => 'Citaat',
                    'velden' => [
                        'text' => 'Citaat',
                    ],
                ],
                'values' => [
                    'naam' => 'Wat je mag verwachten',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'naam' => 'Belofte',
                        'aantal' => 3,
                        'velden' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
                'cta' => $cta,
            ],
        ],

        'practical_information' => [
            'route' => 'praktische-informatie',
            'secties' => [
                'hero' => $hero,
                'information' => [
                    'naam' => 'Informatie',
                    'items' => [
                        'naam' => 'Onderwerp',
                        'aantal' => 8,
                        'velden' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
                'emergency' => [
                    'naam' => 'Noodnummers',
                    'velden' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                    ],
                    'items' => [
                        'naam' => 'Nummer',
                        'aantal' => 3,
                        'velden' => [
                            'name' => 'Naam',
                            'number' => 'Telefoonnummer',
                        ],
                    ],
                ],
                'terms' => [
                    'naam' => 'Voorwaarden',
                    'velden' => [
                        'text' => ['label' => 'Tekst', 'hint' => ':link wordt de link naar de boekingsvoorwaarden.'],
                        'link' => 'Linktekst',
                    ],
                ],
            ],
        ],

        'contact' => [
            'route' => 'contact',
            'secties' => [
                'hero' => $hero,
                'form' => [
                    'naam' => 'Contactformulier',
                    'velden' => [
                        'title' => 'Titel',
                        'name' => 'Naam',
                        'email' => 'E-mailadres',
                        'phone' => 'Telefoonnummer',
                        'optional' => 'Tekst bij niet-verplichte velden',
                        'message' => 'Bericht',
                        'button' => 'Knop',
                        'privacy_text' => ['label' => 'Tekst over privacy', 'hint' => ':link wordt de link naar het privacybeleid.'],
                        'privacy_link' => 'Linktekst privacybeleid',
                        'success_text' => 'Bevestiging na versturen',
                    ],
                ],
                'details' => [
                    'naam' => 'Contactgegevens',
                    'velden' => [
                        'title' => 'Titel',
                        'whatsapp_button' => 'Knop WhatsApp',
                    ],
                ],
                'response_time' => [
                    'naam' => 'Reactietijd',
                    'velden' => [
                        'title' => 'Titel',
                        'text' => 'Tekst',
                    ],
                ],
            ],
        ],

        'booking' => [
            'route' => 'reserveren',
            'secties' => [
                'hero' => $hero,
                'stay' => [
                    'naam' => 'Je verblijf',
                    'velden' => [
                        'title' => 'Titel',
                        'arrival' => 'Aankomst',
                        'departure' => 'Vertrek',
                        'guests' => 'Aantal personen',
                        'person' => 'Eén persoon',
                        'persons' => 'Meerdere personen',
                        'guests_hint' => ['label' => 'Toelichting aantal personen', 'hint' => ':aantal wordt het maximale aantal personen.'],
                    ],
                ],
                'details' => [
                    'naam' => 'Jouw gegevens',
                    'velden' => [
                        'title' => 'Titel',
                        'name' => 'Naam',
                        'email' => 'E-mailadres',
                        'phone' => 'Telefoonnummer',
                        'optional' => 'Tekst bij niet-verplichte velden',
                        'comments' => 'Opmerkingen',
                        'comments_hint' => 'Toelichting opmerkingen',
                        'terms_text' => ['label' => 'Akkoord met de voorwaarden', 'hint' => ':voorwaarden en :privacybeleid worden links.'],
                        'terms_link' => 'Linktekst boekingsvoorwaarden',
                        'privacy_link' => 'Linktekst privacybeleid',
                        'button' => 'Knop',
                        'success_text' => 'Bevestiging na versturen',
                    ],
                ],
                'estimate' => [
                    'naam' => 'Prijsindicatie',
                    'velden' => [
                        'title' => 'Titel',
                        'empty' => 'Tekst zonder gekozen data',
                        ...$prijsindicatie,
                        'deposit_note' => ['label' => 'Toelichting borg', 'hint' => ':bedrag wordt het bedrag van de borg.'],
                        'minimum_stay' => ['label' => 'Melding minimale verblijfsduur', 'hint' => ':aantal wordt het minimale aantal nachten.'],
                        'invalid_dates' => 'Melding bij ongeldige data',
                        'link' => 'Link naar de beschikbaarheid',
                    ],
                ],
                'steps' => [
                    'naam' => 'Zo werkt het',
                    'velden' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'naam' => 'Stap',
                        'aantal' => 3,
                        'velden' => [
                            'text' => 'Tekst',
                        ],
                    ],
                ],
            ],
        ],

        'privacy' => [
            'route' => 'privacy-voorwaarden',
            'secties' => [
                'hero' => $hero,
                'contents' => [
                    'naam' => 'Inhoudsopgave',
                    'velden' => [
                        'title' => 'Titel',
                    ],
                ],
                'booking_terms' => [
                    'naam' => 'Boekingsvoorwaarden',
                    'velden' => [
                        'title' => 'Titel',
                        'booking_title' => 'Kop reserveren',
                        'booking_text' => 'Tekst reserveren',
                        'payment_title' => 'Kop betaling',
                        'payment_text' => 'Tekst betaling',
                        'deposit_title' => 'Kop borg',
                        'deposit_text' => ['label' => 'Tekst borg', 'hint' => ':bedrag wordt het bedrag van de borg.'],
                        'cancellation_title' => 'Kop annuleren',
                        'cancellation_text' => ['label' => 'Tekst annuleren', 'hint' => 'Elke regel wordt een opsommingsteken.'],
                        'liability_title' => 'Kop aansprakelijkheid',
                        'liability_text' => 'Tekst aansprakelijkheid',
                    ],
                ],
                'house_rules' => [
                    'naam' => 'Huisregels',
                    'velden' => [
                        'title' => 'Titel',
                    ],
                ],
                'privacy' => [
                    'naam' => 'Privacyverklaring',
                    'velden' => [
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                        'data_title' => 'Kop gegevens',
                        'data_text' => 'Tekst gegevens',
                        'purpose_title' => 'Kop gebruik',
                        'purpose_text' => 'Tekst gebruik',
                        'retention_title' => 'Kop bewaartermijn',
                        'retention_text' => 'Tekst bewaartermijn',
                        'rights_title' => 'Kop rechten',
                        'rights_text' => ['label' => 'Tekst rechten', 'hint' => ':email wordt het e-mailadres.'],
                    ],
                ],
                'cookies' => [
                    'naam' => 'Cookies',
                    'velden' => [
                        'title' => 'Titel',
                        'text' => 'Tekst',
                    ],
                ],
            ],
        ],

    ],

    'algemeen' => [
        'header' => [
            'naam' => 'Menu bovenaan',
            'velden' => [
                'more' => 'Menu-item voor de overige pagina\'s',
                'button' => 'Knop rechtsboven',
            ],
        ],
        'cta' => [
            'naam' => 'Contactblok onderaan',
            'velden' => [
                'title' => 'Titel',
                'text' => 'Tekst',
                'button' => 'Knop beschikbaarheid',
                'whatsapp_button' => 'Knop WhatsApp',
                'email_label' => 'Tekst vóór het e-mailadres',
            ],
        ],
        'footer' => [
            'naam' => 'Footer',
            'velden' => [
                'contact_title' => 'Kop contact',
                'links_title' => 'Kop links',
                'terms_title' => 'Kop voorwaarden',
                'privacy_link' => 'Link privacyvoorwaarden',
                'cookies_link' => 'Link cookievoorwaarden',
                'terms_link' => 'Link algemene voorwaarden',
                'social_title' => 'Kop social media',
                'copyright' => ['label' => 'Copyright', 'hint' => ':jaar wordt het huidige jaar.'],
            ],
        ],
        'calendar' => [
            'naam' => 'Kalender',
            'velden' => [
                'available' => 'Vrij',
                'booked' => 'Bezet',
                'past' => 'Verstreken',
            ],
        ],
        'form' => [
            'naam' => 'Meldingen in formulieren',
            'velden' => [
                'required' => 'Verplicht veld',
                'email' => 'Ongeldig e-mailadres',
                'too_long' => 'Te lange tekst',
                'date' => 'Ongeldige datum',
                'number' => 'Ongeldig aantal',
                'arrival_past' => 'Aankomst in het verleden',
                'departure_before_arrival' => 'Vertrek vóór aankomst',
                'guests_min' => 'Te weinig personen',
                'guests_max' => ['label' => 'Te veel personen', 'hint' => ':aantal wordt het maximale aantal personen.'],
                'terms' => 'Niet akkoord met de voorwaarden',
                'failed' => 'Formulier niet verstuurd',
            ],
        ],
    ],

];
