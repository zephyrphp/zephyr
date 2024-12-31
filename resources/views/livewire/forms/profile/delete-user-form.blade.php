<x-filament::section aside>
    <x-slot name="heading">
        {{ __('Delete Account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete your account.') }}
    </x-slot>

    <div class="fi-form grid gap-y-6">
        <p class="text-sm leading-6 text-gray-950 dark:text-white">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

        <div class="fi-form-actions">
            <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
                {{ $this->deleteAction }}
            </div>
        </div>
    </div>

    <x-filament-actions::modals/>
</x-filament::section>
