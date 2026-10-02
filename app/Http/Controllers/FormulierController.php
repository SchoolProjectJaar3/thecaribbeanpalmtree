<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Contact- en reserveringsformulier (PVA 6.10).
 *
 * Demo: de aanvraag wordt gevalideerd en bevestigd, maar nog niet opgeslagen
 * of gemaild. Dat volgt met de beheeromgeving (tabellen reserveringen en
 * contactberichten).
 */
class FormulierController extends Controller
{
    public function contact(Request $request): RedirectResponse
    {
        $request->validate([
            'naam' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'telefoon' => ['nullable', 'string', 'max:30'],
            'bericht' => ['required', 'string', 'max:2000'],
            // Spambeveiliging: dit verborgen veld moet leeg blijven.
            'website' => ['nullable', 'max:0'],
        ], $this->meldingen());

        return redirect(route('contact') . '#bevestiging')
            ->with('status', 'Bedankt voor je bericht! We nemen contact met je op en reageren meestal binnen 24 uur.');
    }

    public function reserveren(Request $request): RedirectResponse
    {
        $maxPersonen = 6;

        $request->validate([
            'aankomst' => ['required', 'date', 'after_or_equal:today'],
            'vertrek' => ['required', 'date', 'after:aankomst'],
            'personen' => ['required', 'integer', 'min:1', 'max:' . $maxPersonen],
            'naam' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'telefoon' => ['nullable', 'string', 'max:30'],
            'opmerkingen' => ['nullable', 'string', 'max:2000'],
            'akkoord' => ['accepted'],
            'website' => ['nullable', 'max:0'],
        ], $this->meldingen());

        return redirect(route('reserveren') . '#bevestiging')
            ->with('status', 'Bedankt voor je aanvraag! Wilma bekijkt je aanvraag en keurt die goed of af. Je ontvangt daarna automatisch bericht per e-mail.');
    }

    /** @return array<string, string> */
    private function meldingen(): array
    {
        return [
            'required' => 'Dit veld is verplicht.',
            'email' => 'Vul een geldig e-mailadres in.',
            'max' => 'Dit veld is te lang.',
            'max.0' => 'Dit veld is te lang.',
            'date' => 'Vul een geldige datum in.',
            'aankomst.after_or_equal' => 'De aankomstdatum mag niet in het verleden liggen.',
            'vertrek.after' => 'De vertrekdatum moet na de aankomstdatum liggen.',
            'personen.min' => 'Kies minimaal 1 persoon.',
            'personen.max' => 'De woning biedt plaats aan maximaal 6 personen.',
            'integer' => 'Vul een geldig aantal in.',
            'akkoord.accepted' => 'Ga akkoord met de voorwaarden om je aanvraag te versturen.',
            'website.max' => 'Het formulier kon niet worden verstuurd.',
        ];
    }
}
