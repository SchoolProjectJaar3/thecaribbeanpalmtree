<?php

/*
|--------------------------------------------------------------------------
| Demo-gegevens voor de publieke website
|--------------------------------------------------------------------------
|
| Alle teksten, prijzen, reviews en contactgegevens op de publieke pagina's
| komen uit dit bestand. Het zijn DEMO-WAARDEN: de opdrachtgever levert de
| definitieve content aan. Later komen deze gegevens uit de database
| (tabellen woning, tarieven, reviews, faq, ...) en wordt dit bestand
| vervangen door modellen.
|
*/

return [

    'naam' => 'The Caribbean Palm Tree',
    'eigenaar' => 'Wilma Yard van der Stelt',

    'contact' => [
        'email' => 'stay@thecaribbeanpalmtree.com',
        // Zelfde (voorbeeld)nummer als in de footer.
        'telefoon' => '+31 06 123456789',
        'whatsapp' => '316123456789',
        'reactietijd' => 'binnen 24 uur',
        'tijdsverschil' => 'Curaçao loopt in de winter 5 uur en in de zomer 6 uur achter op Nederland.',
    ],

    // Strook met kerngegevens op de homepage en de pagina "Het huis".
    'kerngegevens' => [
        ['icoon' => 'users', 'label' => 'Personen', 'waarde' => '6 gasten'],
        ['icoon' => 'bed', 'label' => 'Slaapkamers', 'waarde' => '3 kamers'],
        ['icoon' => 'pool', 'label' => 'Zwembad', 'waarde' => 'Privé'],
        ['icoon' => 'snowflake', 'label' => 'Airco', 'waarde' => 'Slaapkamers'],
        ['icoon' => 'wifi', 'label' => 'Wifi', 'waarde' => 'Glasvezel'],
        ['icoon' => 'pin', 'label' => 'Locatie', 'waarde' => 'Harmonie'],
    ],

    // Voorzieningen, gegroepeerd (PVA 6.2).
    'voorzieningen' => [
        [
            'titel' => 'Slapen en wonen',
            'items' => [
                ['icoon' => 'bed', 'titel' => '3 slaapkamers', 'tekst' => 'Rustige, lichte kamers met ruime kasten en uitzicht op de tuin.'],
                ['icoon' => 'bath', 'titel' => '2 badkamers', 'tekst' => 'Met regendouche en voldoende handdoeken voor iedereen.'],
                ['icoon' => 'users', 'titel' => 'Plek voor 6 gasten', 'tekst' => 'Ideaal voor een gezin of een groep vrienden.'],
                ['icoon' => 'crib', 'titel' => 'Kinderbed', 'tekst' => 'Een kinderbed is gratis beschikbaar.'],
            ],
        ],
        [
            'titel' => 'Keuken en huishouden',
            'items' => [
                ['icoon' => 'kitchen', 'titel' => 'Volledig uitgeruste keuken', 'tekst' => 'Kookeiland, gasfornuis en alles om heerlijk te koken.'],
                ['icoon' => 'dishwasher', 'titel' => 'Vaatwasser', 'tekst' => 'Na het eten hoef je niet af te wassen.'],
                ['icoon' => 'washing', 'titel' => 'Wasmachine', 'tekst' => 'Handig bij een langer verblijf.'],
                ['icoon' => 'bbq', 'titel' => 'Barbecue', 'tekst' => 'Grillen onder de sterren op het terras.'],
            ],
        ],
        [
            'titel' => 'Comfort',
            'items' => [
                ['icoon' => 'snowflake', 'titel' => 'Airconditioning', 'tekst' => 'In alle slaapkamers voor een koele nachtrust.'],
                ['icoon' => 'tv', 'titel' => 'Smart-tv', 'tekst' => 'Met Netflix en andere streamingdiensten.'],
                ['icoon' => 'wifi', 'titel' => 'Glasvezelinternet', 'tekst' => 'Snel en stabiel, ook om te werken.'],
            ],
        ],
        [
            'titel' => 'Buiten',
            'items' => [
                ['icoon' => 'pool', 'titel' => 'Privézwembad', 'tekst' => 'Een eigen zwembad, alleen voor jouw gezelschap.'],
                ['icoon' => 'sun', 'titel' => 'Terras', 'tekst' => 'Overdekt terras met ligbedden en loungehoek.'],
                ['icoon' => 'palm', 'titel' => 'Tuin', 'tekst' => 'Tropische tuin met palmbomen.'],
                ['icoon' => 'parking', 'titel' => 'Parkeerplaats', 'tekst' => 'Eigen parkeerplaats op het terrein.'],
            ],
        ],
    ],

    // Indeling per ruimte.
    'ruimtes' => [
        ['titel' => 'Woonkamer', 'foto' => 'woonkamer.webp', 'alt' => 'Woonkamer met stenen muur en openslaande deuren naar het terras', 'tekst' => 'Een ruime woonkamer met een grote bank, plafondventilator en openslaande deuren die uitkomen op het terras.'],
        ['titel' => 'Keuken', 'foto' => 'keuken.jpg', 'alt' => 'Moderne keuken met kookeiland en barkrukken', 'tekst' => 'Een open keuken met kookeiland en barkrukken, zodat de kok altijd bij het gesprek blijft.'],
        ['titel' => 'Slaapkamers', 'foto' => 'slaapkamer.avif', 'alt' => 'Slaapkamer met tweepersoonsbed en airconditioning', 'tekst' => 'Drie slaapkamers met airconditioning, kasten en eigen toegang tot het terras.'],
        ['titel' => 'Zwembad en terras', 'foto' => 'zwembad_terras.avif', 'alt' => 'Privézwembad met houten terras en ligbedden', 'tekst' => 'Het hart van de woning: een privézwembad met een groot houten terras en schaduw onder de palapa.'],
    ],

    // Fotogalerij (PVA 6.3). Categorieën: buiten, drone, binnen.
    'foto_categorieen' => [
        'alle' => 'Alle foto\'s',
        'buiten' => 'Buiten en zwembad',
        'drone' => 'Drone',
        'binnen' => 'Binnen',
    ],
    'fotos' => [
        ['bestand' => 'zwembad_terras.avif', 'titel' => 'Zwembad en terras', 'categorie' => 'buiten', 'alt' => 'Privézwembad met houten terras en ligbedden'],
        ['bestand' => 'grote_banner.webp', 'titel' => 'De woning vanuit de lucht', 'categorie' => 'drone', 'alt' => 'Drone-opname van de woning met zwembad en uitzicht op zee'],
        ['bestand' => 'woonkamer.webp', 'titel' => 'Woonkamer', 'categorie' => 'binnen', 'alt' => 'Woonkamer met stenen muur en openslaande deuren naar het terras'],
        ['bestand' => 'uitzicht.jpg', 'titel' => 'Uitzicht', 'categorie' => 'buiten', 'alt' => 'Uitzicht over het zwembad, de tropische tuin en de zee'],
        ['bestand' => 'keuken.jpg', 'titel' => 'Keuken', 'categorie' => 'binnen', 'alt' => 'Moderne keuken met kookeiland en barkrukken'],
        ['bestand' => 'slaapkamer.avif', 'titel' => 'Slaapkamer', 'categorie' => 'binnen', 'alt' => 'Slaapkamer met tweepersoonsbed en airconditioning'],
    ],

    // Beschikbaarheid (PVA 6.4). Bezette periodes als [dagen na de 1e van
    // de huidige maand, aantal dagen], zodat de demo nooit verouderd raakt.
    'beschikbaarheid' => [
        'bezet' => [
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

    // Tarieven (PVA 6.5).
    'tarieven' => [
        'hoog_maanden' => [12, 1, 2, 3, 4],
        'prijs_laag' => 145,
        'prijs_hoog' => 195,
        'min_nachten_laag' => 5,
        'min_nachten_hoog' => 7,
        'schoonmaak' => 95,
        'borg' => 350,
        'toeristenbelasting' => 3,
        'kortingen' => [
            ['vanaf' => 7, 'procent' => 5],
            ['vanaf' => 14, 'procent' => 10],
            ['vanaf' => 28, 'procent' => 15],
        ],
    ],

    // Omgeving (PVA 6.6). Reistijd in minuten met de auto, afstand in km.
    'afstanden' => [
        'Stranden' => [
            ['naam' => 'Daaibooi Beach', 'minuten' => 14, 'km' => '11,1'],
            ['naam' => 'Kokomo Beach', 'minuten' => 17, 'km' => '12,5'],
            ['naam' => 'Playa Porto Marie', 'minuten' => 17, 'km' => '12,1'],
            ['naam' => 'Cas Abou', 'minuten' => 23, 'km' => '15,3'],
            ['naam' => 'Playa Lagun', 'minuten' => 26, 'km' => '23,4'],
            ['naam' => 'Playa Jeremi', 'minuten' => 28, 'km' => '24,4'],
            ['naam' => 'Playa Grandi', 'minuten' => 31, 'km' => '29,3'],
            ['naam' => 'Kleine Knip', 'minuten' => 32, 'km' => '26,8'],
            ['naam' => 'Grote Knip', 'minuten' => 33, 'km' => '27,8'],
        ],
        'Voorzieningen en steden' => [
            ['naam' => 'Supermarkt Piscadera', 'minuten' => 10, 'km' => '7,9'],
            ['naam' => 'Hato International Airport', 'minuten' => 12, 'km' => '8,8'],
            ['naam' => 'Willemstad', 'minuten' => 18, 'km' => '14,2'],
        ],
        'Restaurants' => [
            ['naam' => 'Grand Café De Gouverneur', 'minuten' => 20, 'km' => '14,5'],
            ['naam' => 'The Captain', 'minuten' => 20, 'km' => '14,5'],
        ],
    ],

    'activiteiten' => [
        ['icoon' => 'water', 'titel' => 'Snorkelen en duiken', 'tekst' => 'Kristalhelder water, koraalriffen en schildpadden vlak bij de kust.'],
        ['icoon' => 'boat', 'titel' => 'Boottochten', 'tekst' => 'Een dagje naar Klein Curaçao of een zonsondergangtocht langs de kust.'],
        ['icoon' => 'mountain', 'titel' => 'Christoffelberg', 'tekst' => 'Beklim de hoogste berg van het eiland en geniet van het uitzicht.'],
        ['icoon' => 'wave', 'titel' => 'Shete Boka', 'tekst' => 'Woeste golven die tegen de rotsen slaan in dit nationale park.'],
        ['icoon' => 'eye', 'titel' => 'Flamingo\'s spotten', 'tekst' => 'Zie flamingo\'s in de zoutpannen en meren op het eiland.'],
        ['icoon' => 'golf', 'titel' => 'Golf', 'tekst' => 'Speel een rondje op een van de golfbanen op het eiland.'],
        ['icoon' => 'map', 'titel' => 'Wandelen', 'tekst' => 'Wandelroutes langs de kust en door het groene binnenland.'],
        ['icoon' => 'umbrella', 'titel' => 'Beachclubs en restaurants', 'tekst' => 'Van ontspannen strandtenten tot chique restaurants in Willemstad.'],
    ],

    'extra_stranden' => ['Playa Forti', 'Playa Kalki', 'Sint Joris Baai', 'Jan Thiel'],

    // Reviews (PVA 6.7). Demo-reviews van fictieve gasten.
    'reviews' => [
        ['naam' => 'Familie de Vries', 'plaats' => 'Utrecht', 'sterren' => 5, 'periode' => 'maart 2026', 'tekst' => 'Een heerlijk huis met een prachtig zwembad. Alles was schoon en tot in de puntjes verzorgd. Binnen een kwartier sta je op het strand!'],
        ['naam' => 'Sanne en Mark', 'plaats' => 'Rotterdam', 'sterren' => 5, 'periode' => 'februari 2026', 'tekst' => 'Wilma dacht overal aan mee en gaf ons de beste tips voor stranden en restaurants. We komen zeker terug.'],
        ['naam' => 'Thomas', 'plaats' => 'Antwerpen', 'sterren' => 4, 'periode' => 'januari 2026', 'tekst' => 'Rustig gelegen en toch centraal. Ideale uitvalsbasis om het hele eiland te ontdekken.'],
        ['naam' => 'Familie Bakker', 'plaats' => 'Amersfoort', 'sterren' => 5, 'periode' => 'december 2025', 'tekst' => 'Onze kinderen wilden het zwembad niet meer uit. De keuken is perfect uitgerust en het kinderbed was een fijne extra.'],
        ['naam' => 'Lisa en Daan', 'plaats' => 'Gorinchem', 'sterren' => 5, 'periode' => 'november 2025', 'tekst' => 'Precies zoals op de foto\'s, misschien nog mooier. De sleuteloverdracht verliep soepel en alles was goed geregeld.'],
        ['naam' => 'Peter', 'plaats' => 'Breda', 'sterren' => 4, 'periode' => 'oktober 2025', 'tekst' => 'Prima verblijf met veel privacy. Een huurauto is wel echt nodig, maar de stranden zijn dan zo bereikt.'],
        ['naam' => 'Familie Janssen', 'plaats' => 'Eindhoven', 'sterren' => 5, 'periode' => 'september 2025', 'tekst' => 'Fantastisch uitzicht en een zwembad om u tegen te zeggen. Wilma reageerde altijd binnen een paar uur.'],
        ['naam' => 'Marieke', 'plaats' => 'Haarlem', 'sterren' => 5, 'periode' => 'augustus 2025', 'tekst' => 'We waren met drie stellen en hadden alle ruimte. De airco in alle slaapkamers was echt een uitkomst.'],
    ],

    // Veelgestelde vragen (PVA 6.8). De eerste zes staan ook op de homepage.
    'faq' => [
        ['vraag' => 'Is een auto noodzakelijk?', 'antwoord' => 'Ja, wij raden een huurauto aan. De woning ligt centraal, maar stranden, supermarkten en Willemstad bereik je het makkelijkst met de auto.'],
        ['vraag' => 'Is er airconditioning aanwezig?', 'antwoord' => 'Ja, alle slaapkamers zijn voorzien van airconditioning.'],
        ['vraag' => 'Hoe werkt de sleuteloverdracht?', 'antwoord' => 'Bij aankomst word je ontvangen door onze lokale contactpersoon, die je de sleutels overhandigt en de woning laat zien.'],
        ['vraag' => 'Zijn huisdieren toegestaan?', 'antwoord' => 'Huisdieren zijn helaas niet toegestaan in de woning.'],
        ['vraag' => 'Mag er gerookt worden?', 'antwoord' => 'Binnen is roken niet toegestaan. Op het terras mag je wel roken.'],
        ['vraag' => 'Hoe werkt annuleren?', 'antwoord' => 'De annuleringsvoorwaarden staan in de boekingsvoorwaarden. Neem bij twijfel gerust contact met ons op.'],
        ['vraag' => 'Hoe werkt een reservering?', 'antwoord' => 'Je vraagt via de website een reservering aan voor vrije data. Wilma bekijkt de aanvraag en keurt die goed of af. Je ontvangt daarna automatisch bericht per e-mail.'],
        ['vraag' => 'Hoe laat kan ik in- en uitchecken?', 'antwoord' => 'Inchecken kan vanaf 15.00 uur en uitchecken kan tot 11.00 uur. Wil je eerder of later? Vraag het gerust, dan kijken we wat mogelijk is.'],
        ['vraag' => 'Is er een kinderbed aanwezig?', 'antwoord' => 'Ja, er is een kinderbed beschikbaar. Laat bij je aanvraag weten dat je het wilt gebruiken, dan staat het klaar.'],
        ['vraag' => 'Is er wifi in de woning?', 'antwoord' => 'Ja, de woning heeft snel glasvezelinternet, ook geschikt om op afstand te werken.'],
    ],

    // Praktische informatie (PVA 6.11).
    'praktisch' => [
        ['icoon' => 'clock', 'titel' => 'In- en uitchecken', 'tekst' => 'Inchecken kan vanaf 15.00 uur, uitchecken kan tot 11.00 uur. Andere tijden bespreken we graag vooraf.'],
        ['icoon' => 'key', 'titel' => 'Sleuteloverdracht', 'tekst' => 'Onze lokale contactpersoon ontvangt je bij aankomst, overhandigt de sleutels en laat de woning zien.'],
        ['icoon' => 'plane', 'titel' => 'Route vanaf de luchthaven', 'tekst' => 'Hato International Airport ligt op 12 minuten rijden (8,8 km). Na bevestiging van je reservering sturen we een routebeschrijving.'],
        ['icoon' => 'car', 'titel' => 'Huurauto', 'tekst' => 'Wij raden een huurauto aan om het eiland te ontdekken. Op de luchthaven vind je diverse verhuurders; reserveer in het hoogseizoen op tijd.'],
        ['icoon' => 'bolt', 'titel' => 'Stroomvoorziening', 'tekst' => 'Op Curaçao kom je zowel 110 V als 220 V tegen. Neem voor Nederlandse apparaten een stekkeradapter mee.'],
        ['icoon' => 'droplet', 'titel' => 'Water', 'tekst' => 'Het leidingwater is veilig om te drinken. Bij aankomst staat er koud drinkwater voor je klaar.'],
        ['icoon' => 'wifi', 'titel' => 'Internet', 'tekst' => 'Snel glasvezelinternet in de hele woning, zonder extra kosten.'],
        ['icoon' => 'shield', 'titel' => 'Veiligheid', 'tekst' => 'De woning heeft een afsluitbaar terrein en een kluis. Laat waardevolle spullen nooit zichtbaar in de auto liggen.'],
    ],

    'noodnummers' => [
        ['naam' => 'Alarmnummer', 'nummer' => '911'],
        ['naam' => 'Lokale contactpersoon', 'nummer' => '+599 9 000 0000'],
        ['naam' => 'Wilma (eigenaar)', 'nummer' => '+31 06 123456789'],
    ],

    // Over de eigenaar (PVA 6.9).
    'eigenaar_verhaal' => [
        'intro' => 'Bij The Caribbean Palm Tree ben je geen boekingsnummer. Er is één woning, één eigenaar en altijd direct contact.',
        'blokken' => [
            ['titel' => 'Waarom ik deze woning verhuur', 'tekst' => 'Curaçao is voor mij een tweede thuis geworden. De rust, de kleuren en de gastvrijheid van het eiland wil ik graag delen. Daarom heb ik het huis zo ingericht dat het voelt als een plek waar je zelf wilt wonen: licht, ruim en met alle comfort.'],
            ['titel' => 'Hoe ik je help', 'tekst' => 'Ik ben er voor je, ook als je al op het eiland bent. Heb je een vraag over de woning, een tip nodig voor een strand of een restaurant, of moet er iets geregeld worden? Een berichtje via WhatsApp is genoeg.'],
        ],
        'waarden' => [
            ['icoon' => 'phone', 'titel' => 'Direct contact', 'tekst' => 'Je praat altijd rechtstreeks met mij, zonder tussenkomst van een platform.'],
            ['icoon' => 'map', 'titel' => 'Lokale tips', 'tekst' => 'Van verborgen baaien tot de beste snorkelplekken: ik deel graag mijn favorieten.'],
            ['icoon' => 'shield', 'titel' => 'Transparant', 'tekst' => 'Duidelijke prijzen en voorwaarden, zonder verborgen kosten.'],
        ],
    ],

];
