<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Contact and booking form (project plan 6.10).
 *
 * Demo: the request is validated and confirmed, but not yet stored or
 * emailed. That follows with the admin area (tables reservations and
 * contact_messages).
 */
class FormController extends Controller
{
    public function contact(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:2000'],
            // Spam protection: this hidden field must stay empty.
            'website' => ['nullable', 'max:0'],
        ], $this->messages());

        return redirect(route('contact') . '#confirmation')
            ->with('status', 'Bedankt voor je bericht! We nemen contact met je op en reageren meestal binnen 24 uur.');
    }

    public function booking(Request $request): RedirectResponse
    {
        $maxGuests = Accommodation::current()?->max_guests;

        $request->validate([
            'arrival' => ['required', 'date', 'after_or_equal:today'],
            'departure' => ['required', 'date', 'after:arrival'],
            'guests' => ['required', 'integer', 'min:1', ...($maxGuests ? ['max:' . $maxGuests] : [])],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'comments' => ['nullable', 'string', 'max:2000'],
            'terms' => ['accepted'],
            'website' => ['nullable', 'max:0'],
        ], $this->messages());

        return redirect(route('booking') . '#confirmation')
            ->with('status', 'Bedankt voor je aanvraag! Wilma bekijkt je aanvraag en keurt die goed of af. Je ontvangt daarna automatisch bericht per e-mail.');
    }

    /** @return array<string, string> */
    private function messages(): array
    {
        return [
            'required' => 'Dit veld is verplicht.',
            'email' => 'Vul een geldig e-mailadres in.',
            'max' => 'Dit veld is te lang.',
            'max.0' => 'Dit veld is te lang.',
            'date' => 'Vul een geldige datum in.',
            'arrival.after_or_equal' => 'De aankomstdatum mag niet in het verleden liggen.',
            'departure.after' => 'De vertrekdatum moet na de aankomstdatum liggen.',
            'guests.min' => 'Kies minimaal 1 persoon.',
            'guests.max' => 'De woning biedt plaats aan maximaal :max personen.',
            'integer' => 'Vul een geldig aantal in.',
            'terms.accepted' => 'Ga akkoord met de voorwaarden om je aanvraag te versturen.',
            'website.max' => 'Het formulier kon niet worden verstuurd.',
        ];
    }
}
