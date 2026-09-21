# The Caribbean Palm Tree

Website gebouwd met [Laravel 13](https://laravel.com/docs/13.x). Alles wat op `main` komt, gaat automatisch online op DirectAdmin.

- Laravel 13 (PHP 8.3 of hoger)
- Vite + Tailwind CSS 4 (Node.js 24 LTS)
- Lokaal SQLite, online MySQL

## Lokaal draaien

Je hebt PHP 8.3+, Composer en Node.js nodig. Op Windows is [Laravel Herd](https://herd.laravel.com) het makkelijkst (PHP + Composer); Node.js haal je van [nodejs.org](https://nodejs.org) (LTS).

Eenmalig, na het clonen:

```bash
composer run setup
```

Dit installeert alle packages, maakt `.env` en een app key aan, zet de SQLite-database klaar en bouwt de frontend.

Daarna, elke keer dat je gaat werken:

```bash
composer run dev
```

De site draait dan op <http://127.0.0.1:8000> en ververst automatisch als je iets aanpast. Tests draaien met `php artisan test`.

## Werkwijze

1. Maak een eigen branch vanaf `main`, bijvoorbeeld `feat/menukaart`.
2. Open een pull request naar `main`. GitHub draait dan automatisch de tests.
3. Na het mergen in `main` staat de site binnen een paar minuten online.

Kom niet aan de branch `deploy`: die wordt automatisch door GitHub Actions gevuld.

## Zo komt de site online

DirectAdmin kan zelf geen `composer install` of `npm run build` draaien, dus dat doet GitHub:

```text
push naar main
  → GitHub Actions: tests, composer install --no-dev, npm run build
  → resultaat (inclusief vendor/ en public/build/) op de branch `deploy`
  → webhook → DirectAdmin haalt `deploy` op en zet hem in public_html
  → .htaccess in de hoofdmap stuurt elk bezoek door naar public/
```

Zie [.github/workflows/deploy.yml](.github/workflows/deploy.yml) en [.htaccess](.htaccess).

## Eenmalig instellen op DirectAdmin

Vervang `jouwdomein.nl` en `gebruiker` hieronder door jullie eigen gegevens.

1. **PHP-versie** – Zet PHP voor het domein op **8.3 of hoger** (bij *Domain Setup* of *Select PHP version*). Laravel 13 werkt niet op PHP 8.2.
2. **Database** – Maak bij *MySQL Management* een database met gebruiker aan. DirectAdmin zet je gebruikersnaam ervoor, bijvoorbeeld `gebruiker_palmtree`.
3. **public_html leegmaken** – Verwijder in de *File Manager* de standaardbestanden uit `domains/jouwdomein.nl/public_html`. Laat de map zelf staan.
4. **Eerste build afwachten** – De branch `deploy` bestaat pas als de workflow op GitHub (tabblad *Actions*) één keer groen is geworden op `main`.
5. **Git-repository koppelen** – Ga naar *Advanced Features → Git → Create Repository*:
   - **Name:** `palmtree`
   - **Remote:** `https://github.com/SchoolProjectJaar3/thecaribbeanpalmtree.git`

   Open daarna de repository en klik op **Fetch**. Kies **Modify** en vul dit in:
   - **Deploy branch:** `deploy`
   - **Deploy directory:** `domains/jouwdomein.nl/public_html` (vanaf je home-map, dus zonder `/home/gebruiker/`)

   Klik op **Save** en daarna op **Deploy**.
6. **`.env` aanmaken** – Maak in de *File Manager* in `public_html` een bestand `.env` aan met de inhoud hieronder. Een `APP_KEY` maak je lokaal aan met `php artisan key:generate --show`.
7. **Automatisch deployen** – Kopieer de **webhook-URL** van de repository in DirectAdmin. Zet die in GitHub bij *Settings → Secrets and variables → Actions → New repository secret*, met als naam `DIRECTADMIN_WEBHOOK_URL`. Zonder dit secret moet je na elke push zelf op **Fetch** en **Deploy** klikken.
8. **HTTPS** – Vraag bij *SSL Certificates* een Let's Encrypt-certificaat aan. Controleer daar ook dat `private_html` een symbolische link naar `public_html` is, anders toont `https://` niet de site.
9. **Controleren** – Op `https://jouwdomein.nl` hoort de Laravel-startpagina te staan. Open ook `https://jouwdomein.nl/.env`: dat **moet** een 404-pagina geven. Zie je daar de inhoud van `.env`, haal het bestand dan meteen weg. De server leest `.htaccess` dan niet (bijvoorbeeld alleen nginx), en dat moet eerst door de hosting worden opgelost.

### `.env` voor de server

```dotenv
APP_NAME="The Caribbean Palm Tree"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://jouwdomein.nl

LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=gebruiker_palmtree
DB_USERNAME=gebruiker_palmtree
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Sessies en cache staan hier op `file` en de queue op `sync`. Zo werkt de site ook zonder migraties, en op gedeelde hosting draait er geen aparte queue-worker. De `.env` blijft bij een deploy gewoon staan.

## Migraties op de server

**Met SSH:**

```bash
cd ~/domains/jouwdomein.nl/public_html && php artisan migrate --force
```

**Zonder SSH:** maak bij *Advanced Features → Cron Jobs* een taak aan die elke minuut draait:

```bash
cd /home/gebruiker/domains/jouwdomein.nl/public_html && /usr/local/bin/php artisan migrate --force >> storage/logs/cron.log 2>&1
```

Wacht een minuut en kijk in `storage/logs/cron.log` of het gelukt is. Verwijder de taak daarna weer. Is de standaard-PHP van de server ouder dan 8.3? Gebruik dan bijvoorbeeld `/usr/local/php83/bin/php`.

## Problemen oplossen

- **Foutmelding 500** – Kijk in `public_html/storage/logs/laravel.log`. Zet voor meer details tijdelijk `APP_DEBUG=true` in `.env`, en daarna weer op `false`.
- **"Composer detected issues in your platform"** – De PHP-versie van het domein is te oud. Zet hem op 8.3 of hoger.
- **Wijziging niet online** – Is de workflow bij *Actions* op GitHub groen? Klik anders in DirectAdmin bij de repository zelf op **Fetch** en **Deploy**.
- **Niets aanpassen via de File Manager** (behalve `.env`): de volgende deploy overschrijft het.
- **Gebruik geen `php artisan optimize` of `config:cache`/`route:cache` op de server.** Na een deploy blijven dan oude instellingen en routes actief. Is het toch gebeurd? Draai dan `php artisan optimize:clear`.
- **PHP-versie van packages** – In `composer.json` staat `config.platform.php` op `8.3.0`. Daardoor installeert Composer alleen packages die ook op PHP 8.3 werken. Verhoog dit pas als de server een nieuwere PHP-versie draait.
