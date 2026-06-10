<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading text-h3">{{ __('Profile') }}</h2>
    </x-slot>

    <div class="page-shell">
        <div class="container-app max-w-3xl space-y-6">
            <x-flash-messages />

            <div class="card card-body">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="card card-body">
                @include('profile.partials.update-password-form')
            </div>

            <div class="card card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
