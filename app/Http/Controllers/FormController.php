<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Contact and booking form (project plan 6.10).
 *
 * Booking requests are stored (see BookingService). Contact messages are
 * not stored yet, and emails follow in sprint 5.
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

    public function booking(Request $request, BookingService $bookings): RedirectResponse
    {
        $accommodation = Accommodation::current();

        $data = $request->validate([
            'arrival' => ['required', 'date', 'after_or_equal:today'],
            'departure' => ['required', 'date', 'after:arrival'],
            'guests' => ['required', 'integer', 'min:1', ...($accommodation ? ['max:' . $accommodation->max_guests] : [])],
            // Separate fields because guests has first_name and last_name;
            // splitting one name field goes wrong with names like "Jan van der Berg".
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'comments' => ['nullable', 'string', 'max:2000'],
            'terms' => ['accepted'],
            'website' => ['nullable', 'max:0'],
        ], $this->messages());

        if ($accommodation === null) {
            return back()->withInput()->withErrors(['arrival' => 'Reserveren is op dit moment niet mogelijk. Neem contact met ons op.']);
        }

        $reservation = $bookings->request($accommodation, $data);

        return redirect(route('booking') . '#confirmation')
            ->with('status', "Bedankt voor je aanvraag! Je reserveringsnummer is {$reservation->reservation_number}. Wilma bekijkt je aanvraag en keurt die goed of af. Je ontvangt daarna bericht per e-mail.");
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
