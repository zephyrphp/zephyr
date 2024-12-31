<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use Carbon\CarbonImmutable;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as FilamentLogoutResponse;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureDates();
        $this->configureFilamentColor();
        $this->configureFilamentLogout();
        $this->configureModels();
        $this->configurePasswordValidation();
        $this->configureUrl();
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );
    }

    /**
     * Configure the dates.
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure the Filament color.
     */
    private function configureFilamentColor(): void
    {
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Gray,
            'info' => Color::Blue,
            'primary' => Color::Indigo,
            'success' => Color::Green,
            'warning' => Color::Orange,
        ]);
    }

    /**
     * Configure the Filament logout response.
     */
    private function configureFilamentLogout(): void
    {
        $this->app->bind(FilamentLogoutResponse::class, LogoutResponse::class);
    }

    /**
     * Configure the application's models.
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict(
            $this->app->isProduction()
        );
    }

    /**
     * Configure the password validation rules.
     */
    private function configurePasswordValidation(): void
    {
        Password::defaults(
            fn () => $this->app->isProduction()
                ? Password::min(8)->letters()->mixedCase()->numbers()->symbols()
                : Password::min(8)
        );
    }

    /**
     * Configure the application's URL.
     */
    private function configureUrl(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
