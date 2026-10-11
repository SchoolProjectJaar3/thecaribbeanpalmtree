@props(['status'])

@php
$kleuren = match ($status) {
    \App\Enums\ReservationStatus::Request => 'bg-sand/25 text-deep-blue ring-sand',
    \App\Enums\ReservationStatus::Confirmed => 'bg-turquoise/10 text-deep-blue ring-turquoise/40',
    \App\Enums\ReservationStatus::DepositPaid => 'bg-turquoise/15 text-deep-blue ring-turquoise/50',
    \App\Enums\ReservationStatus::Paid => 'bg-turquoise text-white ring-turquoise',
    \App\Enums\ReservationStatus::Cancelled => 'bg-coral/10 text-coral ring-coral/30',
    \App\Enums\ReservationStatus::Completed => 'bg-deep-blue/5 text-deep-blue/70 ring-deep-blue/15',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {$kleuren}"]) }}>{{ $status->label() }}</span>
