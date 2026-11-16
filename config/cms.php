<?php

/*
|--------------------------------------------------------------------------
| Website texts
|--------------------------------------------------------------------------
|
| Which texts the owner can edit per page. The keys of pages, sections
| and fields match those in the tables cms_pages, cms_sections and
| cms_contents. The texts themselves are stored in the database and are
| seeded by the CmsSeeder.
|
| A field is a label, or an array with a label and an explanation (hint).
| Fields with the key 'intro' or 'text', or ending in '_intro' or '_text',
| get a large text area in the admin area.
|
| Repeating parts, such as activities, are listed under 'items'. Each item
| gets the key item_{number}_{field}, for example item_1_title.
|
| The 'general' texts appear on every page and are stored in
| cms_translations, with the key {group}.{field}, for example footer.copyright.
|
*/

$hero = [
    'name' => 'Bovenaan de pagina',
    'fields' => [
        'eyebrow' => 'Bovenschrift',
        'title' => 'Titel',
        'intro' => 'Introductie',
    ],
];

$cta = [
    'name' => 'Contactblok onderaan',
    'fields' => [
        'title' => 'Titel',
    ],
];

$priceEstimate = [
    'night' => 'Eén nacht',
    'nights' => 'Meerdere nachten',
    'discount' => 'Korting',
    'cleaning' => 'Schoonmaakkosten',
    'tourist_tax' => 'Toeristenbelasting',
    'total' => 'Totaal',
];

return [

    'pages' => [

        'home' => [
            'route' => 'home',
            'sections' => [
                'hero' => [
                    'name' => 'Bovenaan de pagina',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Beschrijving',
                        'button' => 'Knop beschikbaarheid',
                        'booking_button' => 'Knop boeken',
                    ],
                ],
                'photos' => [
                    'name' => 'Sfeerimpressie',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar de foto\'s',
                    ],
                ],
                'property' => [
                    'name' => 'Het huis',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => 'Korte tekst over het huis',
                        'link' => 'Link naar de voorzieningen',
                    ],
                ],
                'surroundings' => [
                    'name' => 'Omgeving',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => 'Tekst over de omgeving',
                        'link' => 'Link naar de omgeving',
                    ],
                    'items' => [
                        'name' => 'Plek',
                        'count' => 4,
                        'fields' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                            'travel_time' => 'Reistijd',
                        ],
                    ],
                ],
                'availability' => [
                    'name' => 'Beschikbaarheid',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => 'Tekst',
                        'button' => 'Knop',
                    ],
                ],
                'reviews' => [
                    'name' => 'Reviews',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar alle reviews',
                    ],
                ],
                'faq' => [
                    'name' => 'Veelgestelde vragen',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar alle vragen',
                    ],
                ],
                'owner' => [
                    'name' => 'Over de eigenaar',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => 'Tekst',
                        'link' => 'Link naar het verhaal',
                    ],
                ],
                'closing' => [
                    'name' => 'Afsluiting',
                    'fields' => [
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
            'route' => 'property',
            'sections' => [
                'hero' => $hero,
                'description' => [
                    'name' => 'Over de woning',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'text' => ['label' => 'Tekst', 'hint' => 'Een lege regel begint een nieuwe alinea.'],
                        'button' => 'Knop',
                    ],
                ],
                'amenities' => [
                    'name' => 'Voorzieningen',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                    ],
                ],
                'rooms' => [
                    'name' => 'Indeling',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar de foto\'s',
                    ],
                    'items' => [
                        'name' => 'Ruimte',
                        'count' => 4,
                        'fields' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
            ],
        ],

        'photos' => [
            'route' => 'photos',
            'sections' => [
                'hero' => $hero,
                'gallery' => [
                    'name' => 'Fotogalerij',
                    'fields' => [
                        'all_photos' => 'Filter voor alle foto\'s',
                        'text' => 'Opmerking onder de foto\'s',
                    ],
                ],
            ],
        ],

        'availability' => [
            'route' => 'availability',
            'sections' => [
                'hero' => $hero,
                'calendar' => [
                    'name' => 'Kalender',
                    'fields' => [
                        'selected' => 'Legenda: jouw keuze',
                        'unavailable_message' => 'Melding bij bezette dagen',
                    ],
                ],
                'summary' => [
                    'name' => 'Jouw verblijf',
                    'fields' => [
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
                    'name' => 'Minimale verblijfsduur',
                    'fields' => [
                        'title' => 'Titel',
                        'low_season' => 'Laagseizoen',
                        'high_season' => 'Hoogseizoen',
                        'nights' => ['label' => 'Aantal nachten', 'hint' => ':aantal wordt het minimale aantal nachten.'],
                        'text' => 'Tekst',
                    ],
                ],
                'cta' => [
                    'name' => 'Contactblok onderaan',
                    'fields' => [
                        'title' => 'Titel',
                        'text' => 'Tekst',
                    ],
                ],
            ],
        ],

        'rates' => [
            'route' => 'rates',
            'sections' => [
                'hero' => $hero,
                'seasons' => [
                    'name' => 'Prijs per nacht',
                    'fields' => [
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
                    'name' => 'Bijkomende kosten',
                    'fields' => [
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
                    'name' => 'Kortingen',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'from_nights' => ['label' => 'Vanaf aantal nachten', 'hint' => ':aantal wordt het aantal nachten.'],
                        'percentage' => ['label' => 'Korting', 'hint' => ':procent wordt het kortingspercentage.'],
                        'text' => 'Toelichting',
                    ],
                ],
                'calculator' => [
                    'name' => 'Prijsindicatie',
                    'fields' => [
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
                    'name' => 'Jouw indicatie',
                    'fields' => [
                        'title' => 'Titel',
                        'empty' => 'Tekst zonder gekozen data',
                        ...$priceEstimate,
                        'deposit_note' => ['label' => 'Toelichting borg', 'hint' => ':bedrag wordt het bedrag van de borg.'],
                        'minimum_stay' => ['label' => 'Melding minimale verblijfsduur', 'hint' => ':aantal wordt het minimale aantal nachten.'],
                        'button' => 'Knop',
                    ],
                ],
            ],
        ],

        'surroundings' => [
            'route' => 'surroundings',
            'sections' => [
                'hero' => $hero,
                'distances' => [
                    'name' => 'Afstanden',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                        'destination' => 'Kolom bestemming',
                        'travel_time' => 'Kolom reistijd',
                        'distance' => 'Kolom afstand',
                    ],
                ],
                'beaches' => [
                    'name' => 'Stranden',
                    'fields' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'name' => 'Strand',
                        'count' => 9,
                        'fields' => [
                            'name' => 'Naam',
                            'travel_time' => 'Reistijd',
                            'distance' => 'Afstand',
                        ],
                    ],
                ],
                'amenities' => [
                    'name' => 'Voorzieningen en steden',
                    'fields' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'name' => 'Bestemming',
                        'count' => 3,
                        'fields' => [
                            'name' => 'Naam',
                            'travel_time' => 'Reistijd',
                            'distance' => 'Afstand',
                        ],
                    ],
                ],
                'restaurants' => [
                    'name' => 'Restaurants',
                    'fields' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'name' => 'Restaurant',
                        'count' => 2,
                        'fields' => [
                            'name' => 'Naam',
                            'travel_time' => 'Reistijd',
                            'distance' => 'Afstand',
                        ],
                    ],
                ],
                'more_beaches' => [
                    'name' => 'Meer stranden',
                    'fields' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'name' => 'Strand',
                        'count' => 4,
                        'fields' => [
                            'name' => 'Naam',
                        ],
                    ],
                ],
                'map' => [
                    'name' => 'Kaart',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'link' => 'Link naar Google Maps',
                    ],
                ],
                'activities' => [
                    'name' => 'Activiteiten',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                    ],
                    'items' => [
                        'name' => 'Activiteit',
                        'count' => 8,
                        'fields' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
            ],
        ],

        'reviews' => [
            'route' => 'reviews',
            'sections' => [
                'hero' => $hero,
                'summary' => [
                    'name' => 'Samenvatting',
                    'fields' => [
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
            'sections' => [
                'hero' => $hero,
                'contact' => [
                    'name' => 'Vraag niet gevonden',
                    'fields' => [
                        'title' => 'Titel',
                        'text' => 'Tekst',
                        'button' => 'Knop',
                    ],
                ],
            ],
        ],

        'owner' => [
            'route' => 'owner',
            'sections' => [
                'hero' => [
                    'name' => 'Bovenaan de pagina',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                    ],
                ],
                'story' => [
                    'name' => 'Verhaal',
                    'items' => [
                        'name' => 'Blok',
                        'count' => 2,
                        'fields' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
                'quote' => [
                    'name' => 'Citaat',
                    'fields' => [
                        'text' => 'Citaat',
                    ],
                ],
                'values' => [
                    'name' => 'Wat je mag verwachten',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'name' => 'Belofte',
                        'count' => 3,
                        'fields' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
                'cta' => $cta,
            ],
        ],

        'practical_information' => [
            'route' => 'practical-information',
            'sections' => [
                'hero' => $hero,
                'information' => [
                    'name' => 'Informatie',
                    'items' => [
                        'name' => 'Onderwerp',
                        'count' => 8,
                        'fields' => [
                            'title' => 'Titel',
                            'text' => 'Tekst',
                        ],
                    ],
                ],
                'emergency' => [
                    'name' => 'Noodnummers',
                    'fields' => [
                        'eyebrow' => 'Bovenschrift',
                        'title' => 'Titel',
                        'intro' => 'Introductie',
                    ],
                    'items' => [
                        'name' => 'Nummer',
                        'count' => 3,
                        'fields' => [
                            'name' => 'Naam',
                            'number' => 'Telefoonnummer',
                        ],
                    ],
                ],
                'terms' => [
                    'name' => 'Voorwaarden',
                    'fields' => [
                        'text' => ['label' => 'Tekst', 'hint' => ':link wordt de link naar de boekingsvoorwaarden.'],
                        'link' => 'Linktekst',
                    ],
                ],
            ],
        ],

        'contact' => [
            'route' => 'contact',
            'sections' => [
                'hero' => $hero,
                'form' => [
                    'name' => 'Contactformulier',
                    'fields' => [
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
                    'name' => 'Contactgegevens',
                    'fields' => [
                        'title' => 'Titel',
                        'whatsapp_button' => 'Knop WhatsApp',
                    ],
                ],
                'response_time' => [
                    'name' => 'Reactietijd',
                    'fields' => [
                        'title' => 'Titel',
                        'text' => 'Tekst',
                    ],
                ],
            ],
        ],

        'booking' => [
            'route' => 'booking',
            'sections' => [
                'hero' => $hero,
                'stay' => [
                    'name' => 'Je verblijf',
                    'fields' => [
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
                    'name' => 'Jouw gegevens',
                    'fields' => [
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
                    'name' => 'Prijsindicatie',
                    'fields' => [
                        'title' => 'Titel',
                        'empty' => 'Tekst zonder gekozen data',
                        ...$priceEstimate,
                        'deposit_note' => ['label' => 'Toelichting borg', 'hint' => ':bedrag wordt het bedrag van de borg.'],
                        'minimum_stay' => ['label' => 'Melding minimale verblijfsduur', 'hint' => ':aantal wordt het minimale aantal nachten.'],
                        'invalid_dates' => 'Melding bij ongeldige data',
                        'link' => 'Link naar de beschikbaarheid',
                    ],
                ],
                'steps' => [
                    'name' => 'Zo werkt het',
                    'fields' => [
                        'title' => 'Titel',
                    ],
                    'items' => [
                        'name' => 'Stap',
                        'count' => 3,
                        'fields' => [
                            'text' => 'Tekst',
                        ],
                    ],
                ],
            ],
        ],

        'privacy' => [
            'route' => 'privacy',
            'sections' => [
                'hero' => $hero,
                'contents' => [
                    'name' => 'Inhoudsopgave',
                    'fields' => [
                        'title' => 'Titel',
                    ],
                ],
                'booking_terms' => [
                    'name' => 'Boekingsvoorwaarden',
                    'fields' => [
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
                    'name' => 'Huisregels',
                    'fields' => [
                        'title' => 'Titel',
                    ],
                ],
                'privacy' => [
                    'name' => 'Privacyverklaring',
                    'fields' => [
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
                    'name' => 'Cookies',
                    'fields' => [
                        'title' => 'Titel',
                        'text' => 'Tekst',
                    ],
                ],
            ],
        ],

    ],

    'general' => [
        'header' => [
            'name' => 'Menu bovenaan',
            'fields' => [
                'more' => 'Menu-item voor de overige pagina\'s',
                'button' => 'Knop rechtsboven',
            ],
        ],
        'cta' => [
            'name' => 'Contactblok onderaan',
            'fields' => [
                'title' => 'Titel',
                'text' => 'Tekst',
                'button' => 'Knop beschikbaarheid',
                'whatsapp_button' => 'Knop WhatsApp',
                'email_label' => 'Tekst vóór het e-mailadres',
            ],
        ],
        'footer' => [
            'name' => 'Footer',
            'fields' => [
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
            'name' => 'Kalender',
            'fields' => [
                'available' => 'Vrij',
                'booked' => 'Bezet',
                'past' => 'Verstreken',
            ],
        ],
        'form' => [
            'name' => 'Meldingen in formulieren',
            'fields' => [
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
