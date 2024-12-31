<x-filament::section aside>
    <x-slot name="heading">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __("Update your account's profile information and email address.") }}
    </x-slot>

    <form class="fi-form grid gap-y-6" wire:submit="updateUserProfileInformation">
        {{ $this->form }}

        <div class="fi-form-actions">
            <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
                {{ $this->submitAction }}
            </div>
        </div>
    </form>

    <x-filament-actions::modals/>
</x-filament::section>
