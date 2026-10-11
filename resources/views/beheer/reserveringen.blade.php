<x-beheer-layout
    title="Reserveringen"
    description="Beoordeel nieuwe aanvragen en houd overzicht over alle reserveringen.">

    <x-beheer.placeholder :features="[
        'Nieuwe aanvragen goedkeuren of afwijzen, waarna de gast automatisch een e-mail ontvangt.',
        'Alle reserveringen zoeken en filteren op status en periode.',
        'Per reservering de gast, het aantal personen, de prijsopbouw en de betalingen bekijken.',
        'Interne notities toevoegen die alleen in de beheeromgeving zichtbaar zijn.',
    ]">
        <div class="mx-auto mt-6 max-w-xl text-left">
            <p class="text-sm font-semibold text-deep-blue">Statussen van een reservering</p>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach (\App\Enums\ReservationStatus::cases() as $status)
                <x-beheer.status-badge :status="$status" />
                @endforeach
            </div>
        </div>
    </x-beheer.placeholder>

</x-beheer-layout>
