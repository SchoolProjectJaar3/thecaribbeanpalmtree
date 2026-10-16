<div class="sticky bottom-4 z-20 flex items-center justify-between gap-4 rounded-xl border border-deep-blue/10 bg-white/95 p-3 shadow-lg backdrop-blur sm:p-4">
    <p class="text-sm text-deep-blue/70" aria-live="polite">
        <span x-show="gewijzigd" x-cloak class="font-medium text-coral">Je hebt wijzigingen die nog niet zijn opgeslagen.</span>
        <span x-show="! gewijzigd" class="hidden sm:inline">Wijzigingen worden bewaard zodra je op Opslaan klikt.</span>
    </p>
    <x-primary-button>Opslaan</x-primary-button>
</div>
