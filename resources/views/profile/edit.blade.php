<x-beheer-layout
    title="Mijn account"
    description="Beheer je persoonlijke gegevens en wachtwoord.">

    <div class="space-y-6">
        <div class="rounded-xl border border-deep-blue/10 bg-white p-4 shadow-sm sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="rounded-xl border border-deep-blue/10 bg-white p-4 shadow-sm sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-xl border border-deep-blue/10 bg-white p-4 shadow-sm sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

</x-beheer-layout>
