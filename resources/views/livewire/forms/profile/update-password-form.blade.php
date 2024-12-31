<x-filament::section aside>
    <x-slot name="heading">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <form class="fi-form grid gap-y-6" wire:submit="updatePassword">
        {{ $this->form }}

        <div class="fi-form-actions">
            <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
                {{ $this->submitAction }}
            </div>
        </div>
    </form>

    <x-filament-actions::modals/>
</x-filament::section>
