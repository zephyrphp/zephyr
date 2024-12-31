@auth
    <x-filament::button :href="route('filament.app.pages.dashboard')" tag="a">
        {{ __('Dashboard') }}
    </x-filament::button>
@else
    <x-filament::button :href="route('filament.app.auth.login')" icon="heroicon-m-sparkles" tag="a">
        {{ __('Get started') }}
    </x-filament::button>
@endauth
