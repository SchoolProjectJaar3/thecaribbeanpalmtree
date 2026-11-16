<?php

/*
|--------------------------------------------------------------------------
| Demo data for the public website
|--------------------------------------------------------------------------
|
| All texts, prices, reviews and contact details on the public pages come
| from this file. These are DEMO VALUES: the client supplies the final
| content. Later this data will come from the database (tables
| accommodations, rates, reviews, faq, ...) and this file will be replaced
| by models.
|
| The features, description, house rules and facilities of the
| accommodation already come from the database and are managed via
| Admin > Woninggegevens.
|
*/

return [

    'name' => 'The Caribbean Palm Tree',
    'owner' => 'Wilma Yard van der Stelt',

    'contact' => [
        'email' => 'stay@thecaribbeanpalmtree.com',
        // Same (example) number as in the footer.
        'phone' => '+31 06 123456789',
        'whatsapp' => '316123456789',
        'response_time' => 'binnen 24 uur',
        'time_difference' => 'Curaçao loopt in de winter 5 uur en in de zomer 6 uur achter op Nederland.',
    ],

    // Layout per room.
    'rooms' => [
        ['title' => 'Woonkamer', 'photo' => 'living_room.webp', 'alt' => 'Woonkamer met stenen muur en openslaande deuren naar het terras', 'text' => 'Een ruime woonkamer met een grote bank, plafondventilator en openslaande deuren die uitkomen op het terras.'],
        ['title' => 'Keuken', 'photo' => 'kitchen.jpg', 'alt' => 'Moderne keuken met kookeiland en barkrukken', 'text' => 'Een open keuken met kookeiland en barkrukken, zodat de kok altijd bij het gesprek blijft.'],
        ['title' => 'Slaapkamers', 'photo' => 'bedroom.avif', 'alt' => 'Slaapkamer met tweepersoonsbed en airconditioning', 'text' => 'Drie slaapkamers met airconditioning, kasten en eigen toegang tot het terras.'],
        ['title' => 'Zwembad en terras', 'photo' => 'pool_terrace.avif', 'alt' => 'Privézwembad met houten terras en ligbedden', 'text' => 'Het hart van de woning: een privézwembad met een groot houten terras en schaduw onder de palapa.'],
    ],

    // Photo gallery (project plan 6.3). Categories: outside, drone, inside.
    'photo_categories' => [
        'all' => 'Alle foto\'s',
        'outside' => 'Buiten en zwembad',
        'drone' => 'Drone',
        'inside' => 'Binnen',
    ],
    'photos' => [
        ['file' => 'pool_terrace.avif', 'title' => 'Zwembad en terras', 'category' => 'outside', 'alt' => 'Privézwembad met houten terras en ligbedden'],
        ['file' => 'hero_banner.webp', 'title' => 'De woning vanuit de lucht', 'category' => 'drone', 'alt' => 'Drone-opname van de woning met zwembad en uitzicht op zee'],
        ['file' => 'living_room.webp', 'title' => 'Woonkamer', 'category' => 'inside', 'alt' => 'Woonkamer met stenen muur en openslaande deuren naar het terras'],
        ['file' => 'view.jpg', 'title' => 'Uitzicht', 'category' => 'outside', 'alt' => 'Uitzicht over het zwembad, de tropische tuin en de zee'],
        ['file' => 'kitchen.jpg', 'title' => 'Keuken', 'category' => 'inside', 'alt' => 'Moderne keuken met kookeiland en barkrukken'],
        ['file' => 'bedroom.avif', 'title' => 'Slaapkamer', 'category' => 'inside', 'alt' => 'Slaapkamer met tweepersoonsbed en airconditioning'],
    ],

    // Availability (project plan 6.4). Booked periods as [days after the 1st
    // of the current month, number of days], so the demo never gets outdated.
    'availability' => [
        'booked' => [
            [6, 6],
            [20, 5],
            [38, 8],
            [57, 9],
            [78, 6],
            [96, 10],
            [124, 7],
            [146, 8],
        ],
    ],

    // Rates (project plan 6.5).
    'rates' => [
        'high_season_months' => [12, 1, 2, 3, 4],
        'price_low' => 145,
        'price_high' => 195,
        'min_nights_low' => 5,
        'min_nights_high' => 7,
        'cleaning' => 95,
        'deposit' => 350,
        'tourist_tax' => 3,
        'discounts' => [
            ['from' => 7, 'percent' => 5],
            ['from' => 14, 'percent' => 10],
            ['from' => 28, 'percent' => 15],
        ],
    ],

    // Surroundings (project plan 6.6). Travel time in minutes by car, distance in km.
    'distances' => [
        'Stranden' => [
            ['name' => 'Daaibooi Beach', 'minutes' => 14, 'km' => '11,1'],
            ['name' => 'Kokomo Beach', 'minutes' => 17, 'km' => '12,5'],
            ['name' => 'Playa Porto Marie', 'minutes' => 17, 'km' => '12,1'],
            ['name' => 'Cas Abou', 'minutes' => 23, 'km' => '15,3'],
            ['name' => 'Playa Lagun', 'minutes' => 26, 'km' => '23,4'],
            ['name' => 'Playa Jeremi', 'minutes' => 28, 'km' => '24,4'],
            ['name' => 'Playa Grandi', 'minutes' => 31, 'km' => '29,3'],
            ['name' => 'Kleine Knip', 'minutes' => 32, 'km' => '26,8'],
            ['name' => 'Grote Knip', 'minutes' => 33, 'km' => '27,8'],
        ],
        'Voorzieningen en steden' => [
            ['name' => 'Supermarkt Piscadera', 'minutes' => 10, 'km' => '7,9'],
            ['name' => 'Hato International Airport', 'minutes' => 12, 'km' => '8,8'],
            ['name' => 'Willemstad', 'minutes' => 18, 'km' => '14,2'],
        ],
        'Restaurants' => [
            ['name' => 'Grand Café De Gouverneur', 'minutes' => 20, 'km' => '14,5'],
            ['name' => 'The Captain', 'minutes' => 20, 'km' => '14,5'],
        ],
    ],

    'activities' => [
        ['icon' => 'water', 'title' => 'Snorkelen en duiken', 'text' => 'Kristalhelder water, koraalriffen en schildpadden vlak bij de kust.'],
        ['icon' => 'boat', 'title' => 'Boottochten', 'text' => 'Een dagje naar Klein Curaçao of een zonsondergangtocht langs de kust.'],
        ['icon' => 'mountain', 'title' => 'Christoffelberg', 'text' => 'Beklim de hoogste berg van het eiland en geniet van het uitzicht.'],
        ['icon' => 'wave', 'title' => 'Shete Boka', 'text' => 'Woeste golven die tegen de rotsen slaan in dit nationale park.'],
        ['icon' => 'eye', 'title' => 'Flamingo\'s spotten', 'text' => 'Zie flamingo\'s in de zoutpannen en meren op het eiland.'],
        ['icon' => 'golf', 'title' => 'Golf', 'text' => 'Speel een rondje op een van de golfbanen op het eiland.'],
        ['icon' => 'map', 'title' => 'Wandelen', 'text' => 'Wandelroutes langs de kust en door het groene binnenland.'],
        ['icon' => 'umbrella', 'title' => 'Beachclubs en restaurants', 'text' => 'Van ontspannen strandtenten tot chique restaurants in Willemstad.'],
    ],

    'extra_beaches' => ['Playa Forti', 'Playa Kalki', 'Sint Joris Baai', 'Jan Thiel'],

    // Reviews (project plan 6.7). Demo reviews from fictional guests.
    'reviews' => [
        ['name' => 'Familie de Vries', 'city' => 'Utrecht', 'stars' => 5, 'period' => 'maart 2026', 'text' => 'Een heerlijk huis met een prachtig zwembad. Alles was schoon en tot in de puntjes verzorgd. Binnen een kwartier sta je op het strand!'],
        ['name' => 'Sanne en Mark', 'city' => 'Rotterdam', 'stars' => 5, 'period' => 'februari 2026', 'text' => 'Wilma dacht overal aan mee en gaf ons de beste tips voor stranden en restaurants. We komen zeker terug.'],
        ['name' => 'Thomas', 'city' => 'Antwerpen', 'stars' => 4, 'period' => 'januari 2026', 'text' => 'Rustig gelegen en toch centraal. Ideale uitvalsbasis om het hele eiland te ontdekken.'],
        ['name' => 'Familie Bakker', 'city' => 'Amersfoort', 'stars' => 5, 'period' => 'december 2025', 'text' => 'Onze kinderen wilden het zwembad niet meer uit. De keuken is perfect uitgerust en het kinderbed was een fijne extra.'],
        ['name' => 'Lisa en Daan', 'city' => 'Gorinchem', 'stars' => 5, 'period' => 'november 2025', 'text' => 'Precies zoals op de foto\'s, misschien nog mooier. De sleuteloverdracht verliep soepel en alles was goed geregeld.'],
        ['name' => 'Peter', 'city' => 'Breda', 'stars' => 4, 'period' => 'oktober 2025', 'text' => 'Prima verblijf met veel privacy. Een huurauto is wel echt nodig, maar de stranden zijn dan zo bereikt.'],
        ['name' => 'Familie Janssen', 'city' => 'Eindhoven', 'stars' => 5, 'period' => 'september 2025', 'text' => 'Fantastisch uitzicht en een zwembad om u tegen te zeggen. Wilma reageerde altijd binnen een paar uur.'],
        ['name' => 'Marieke', 'city' => 'Haarlem', 'stars' => 5, 'period' => 'augustus 2025', 'text' => 'We waren met drie stellen en hadden alle ruimte. De airco in alle slaapkamers was echt een uitkomst.'],
    ],

    // Frequently asked questions (project plan 6.8). The first six are also on the homepage.
    'faq' => [
        ['question' => 'Is een auto noodzakelijk?', 'answer' => 'Ja, wij raden een huurauto aan. De woning ligt centraal, maar stranden, supermarkten en Willemstad bereik je het makkelijkst met de auto.'],
        ['question' => 'Is er airconditioning aanwezig?', 'answer' => 'Ja, alle slaapkamers zijn voorzien van airconditioning.'],
        ['question' => 'Hoe werkt de sleuteloverdracht?', 'answer' => 'Bij aankomst word je ontvangen door onze lokale contactpersoon, die je de sleutels overhandigt en de woning laat zien.'],
        ['question' => 'Zijn huisdieren toegestaan?', 'answer' => 'Huisdieren zijn helaas niet toegestaan in de woning.'],
        ['question' => 'Mag er gerookt worden?', 'answer' => 'Binnen is roken niet toegestaan. Op het terras mag je wel roken.'],
        ['question' => 'Hoe werkt annuleren?', 'answer' => 'De annuleringsvoorwaarden staan in de boekingsvoorwaarden. Neem bij twijfel gerust contact met ons op.'],
        ['question' => 'Hoe werkt een reservering?', 'answer' => 'Je vraagt via de website een reservering aan voor vrije data. Wilma bekijkt de aanvraag en keurt die goed of af. Je ontvangt daarna automatisch bericht per e-mail.'],
        ['question' => 'Hoe laat kan ik in- en uitchecken?', 'answer' => 'Inchecken kan vanaf 15.00 uur en uitchecken kan tot 11.00 uur. Wil je eerder of later? Vraag het gerust, dan kijken we wat mogelijk is.'],
        ['question' => 'Is er een kinderbed aanwezig?', 'answer' => 'Ja, er is een kinderbed beschikbaar. Laat bij je aanvraag weten dat je het wilt gebruiken, dan staat het klaar.'],
        ['question' => 'Is er wifi in de woning?', 'answer' => 'Ja, de woning heeft snel glasvezelinternet, ook geschikt om op afstand te werken.'],
    ],

    // Practical information (project plan 6.11).
    'practical_information' => [
        ['icon' => 'clock', 'title' => 'In- en uitchecken', 'text' => 'Inchecken kan vanaf 15.00 uur, uitchecken kan tot 11.00 uur. Andere tijden bespreken we graag vooraf.'],
        ['icon' => 'key', 'title' => 'Sleuteloverdracht', 'text' => 'Onze lokale contactpersoon ontvangt je bij aankomst, overhandigt de sleutels en laat de woning zien.'],
        ['icon' => 'plane', 'title' => 'Route vanaf de luchthaven', 'text' => 'Hato International Airport ligt op 12 minuten rijden (8,8 km). Na bevestiging van je reservering sturen we een routebeschrijving.'],
        ['icon' => 'car', 'title' => 'Huurauto', 'text' => 'Wij raden een huurauto aan om het eiland te ontdekken. Op de luchthaven vind je diverse verhuurders; reserveer in het hoogseizoen op tijd.'],
        ['icon' => 'bolt', 'title' => 'Stroomvoorziening', 'text' => 'Op Curaçao kom je zowel 110 V als 220 V tegen. Neem voor Nederlandse apparaten een stekkeradapter mee.'],
        ['icon' => 'droplet', 'title' => 'Water', 'text' => 'Het leidingwater is veilig om te drinken. Bij aankomst staat er koud drinkwater voor je klaar.'],
        ['icon' => 'wifi', 'title' => 'Internet', 'text' => 'Snel glasvezelinternet in de hele woning, zonder extra kosten.'],
        ['icon' => 'shield', 'title' => 'Veiligheid', 'text' => 'De woning heeft een afsluitbaar terrein en een kluis. Laat waardevolle spullen nooit zichtbaar in de auto liggen.'],
    ],

    'emergency_numbers' => [
        ['name' => 'Alarmnummer', 'number' => '911'],
        ['name' => 'Lokale contactpersoon', 'number' => '+599 9 000 0000'],
        ['name' => 'Wilma (eigenaar)', 'number' => '+31 06 123456789'],
    ],

    // About the owner (project plan 6.9).
    'owner_story' => [
        'intro' => 'Bij The Caribbean Palm Tree ben je geen boekingsnummer. Er is één woning, één eigenaar en altijd direct contact.',
        'blocks' => [
            ['title' => 'Waarom ik deze woning verhuur', 'text' => 'Curaçao is voor mij een tweede thuis geworden. De rust, de kleuren en de gastvrijheid van het eiland wil ik graag delen. Daarom heb ik het huis zo ingericht dat het voelt als een plek waar je zelf wilt wonen: licht, ruim en met alle comfort.'],
            ['title' => 'Hoe ik je help', 'text' => 'Ik ben er voor je, ook als je al op het eiland bent. Heb je een vraag over de woning, een tip nodig voor een strand of een restaurant, of moet er iets geregeld worden? Een berichtje via WhatsApp is genoeg.'],
        ],
        'values' => [
            ['icon' => 'phone', 'title' => 'Direct contact', 'text' => 'Je praat altijd rechtstreeks met mij, zonder tussenkomst van een platform.'],
            ['icon' => 'map', 'title' => 'Lokale tips', 'text' => 'Van verborgen baaien tot de beste snorkelplekken: ik deel graag mijn favorieten.'],
            ['icon' => 'shield', 'title' => 'Transparant', 'text' => 'Duidelijke prijzen en voorwaarden, zonder verborgen kosten.'],
        ],
    ],

];
