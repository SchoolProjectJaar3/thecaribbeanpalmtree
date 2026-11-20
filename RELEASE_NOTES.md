# Release notes – The Caribbean Palm Tree

**Stand van zaken:** `main` @ `ef7a918` (16 november 2026)
**Periode:** 18 september – 16 november 2026 · 109 commits · PR's t/m #155

Website voor het vakantiehuis The Caribbean Palm Tree: een publieke site waar gasten de woning bekijken en een aanvraag doen, en een beheeromgeving waarin de eigenaar (Wilma) de site beheert.

---

## Techniek en livegang

- **Laravel 13** (PHP 8.3+), Blade, **Vite + Tailwind CSS 4**, Alpine.js. Lokaal SQLite, online MySQL.
- **Automatische deploy:** elke push naar `main` → GitHub Actions draait de tests, doet `composer install --no-dev` en `npm run build`, en zet het resultaat op de branch `deploy`. Een webhook laat DirectAdmin die branch ophalen in `public_html`. Een `.htaccess` in de hoofdmap stuurt alles door naar `public/`.
- Pull requests naar `main` worden automatisch getest. Dependabot houdt npm- en Composer-packages bij.
- In de README staat de volledige handleiding voor het eenmalig instellen van DirectAdmin, de `.env` voor productie, migraties (met en zonder SSH) en het oplossen van problemen.

## Publieke website

Alle pagina's staan er, in de huisstijl, met responsive header en footer:

Home · Het huis · Foto's · Beschikbaarheid (kalender) · Tarieven · Omgeving · Reviews · FAQ · Over de eigenaar · Praktische informatie · Privacy & voorwaarden · Contact · Boeken

- **Contact- en boekingsformulier:** de invoer wordt gecontroleerd (o.a. data, maximaal aantal gasten), met Nederlandse foutmeldingen, een honeypot tegen spam en een rate limit (6 per minuut).
- **Uit de database:** woninggegevens (op home, het huis, boeken, tarieven en privacy) en de CMS-teksten van **Home** en **Het huis**.
- **Nog demo-waarden** (uit `config/site.php` en `config/woning.php`): tarieven, beschikbaarheid, foto's, reviews, FAQ en de overige pagina-teksten.

## Beheeromgeving (`/admin`)

- **Inloggen** met Laravel Breeze, in de huisstijl. **Registreren is uitgeschakeld.** Accounts kunnen op inactief worden gezet en worden dan direct uitgelogd. Rollen: `admin` en `owner`.
- **Dashboard:** open aanvragen, aankomsten in de komende 30 dagen, nieuwe berichten, bezetting deze maand, en lijsten met komende reserveringen en aanvragen.
- **Teksten (CMS):** de teksten per pagina en de algemene teksten zijn te bewerken.
- **Woning:** naam, beschrijving en kenmerken van de woning zijn te bewerken.
- **Placeholders** (de pagina beschrijft wat er komt): reserveringen, beschikbaarheid, tarieven en foto's.

## Database

- **41 migraties** volgens het datamodel: talen (NL/EN), rollen en rechten, gebruikers, woning en faciliteiten (meertalig), seizoenen, tarieven, toeslagen en kortingen, geblokkeerde periodes, gasten, reserveringen met regels en betalingen, reviews, contactberichten, het CMS (pagina's, secties, media, galerij, FAQ, eigenaar, contactgegevens, social media), auditlog en inlogpogingen.
- **Models** voor onder meer woning, gasten, reserveringen (met de enum `ReservationStatus`), seizoenen, tarieven, geblokkeerde periodes en het CMS.
- **Seeders** voor talen, rollen, woning, seizoenen, tarieven, reserveringen, geblokkeerde periodes en alle CMS-teksten. De beheerder wordt alleen aangemaakt als `ADMIN_SEED_EMAIL` en `ADMIN_SEED_PASSWORD` in `.env` staan.

## Kwaliteit

- **113 tests (Pest), 547 assertions, allemaal geslaagd.** Ze dekken de pagina's, de formulieren, de authenticatie, de toegang tot het beheer, het CMS en het woningbeheer.
- De frontend-build (`npm run build`) slaagt.
- De code is volledig in het Engels. De teksten op de site zijn Nederlands.
- Er is een gedragscode voor het team, met afspraken over AI-gebruik (`CODE_OF_CONDUCT.md`).

## Nog niet af (bekende beperkingen)

1. De formulieren **slaan niets op en versturen geen e-mail**: de gast krijgt alleen een bevestiging te zien.
2. In het beheer kun je **reserveringen, beschikbaarheid, tarieven en foto's** nog niet beheren.
3. Tarieven, beschikbaarheid, foto's, reviews en FAQ op de publieke site gebruiken nog **demo-gegevens**. De definitieve prijzen moeten nog van Wilma komen.
4. Behalve Home en Het huis halen de pagina's hun teksten **nog niet uit het CMS**, hoewel ze al in het beheer te bewerken zijn.
5. De site is alleen in het Nederlands. Engels zit wel al in het datamodel.

## Checklist voor de release

- [ ] Op de server staan PHP 8.3+, de MySQL-database, de `.env` (`APP_DEBUG=false`) en HTTPS ingesteld.
- [ ] `php artisan migrate --force` en de seeders zijn gedraaid, met de beheerder via `ADMIN_SEED_*`.
- [ ] `https://<domein>/.env` geeft een 404.
- [ ] Het secret `DIRECTADMIN_WEBHOOK_URL` staat in GitHub.
- [ ] Er is besloten wat er gebeurt met de demo-gegevens en de formulieren die niets opslaan (zie hierboven).
