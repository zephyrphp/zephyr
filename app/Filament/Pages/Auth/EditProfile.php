<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Pages\Page;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;

final class EditProfile extends Page
{
    protected static bool $isDiscovered = false;

    protected static ?string $slug = 'profile';

    protected static string $view = 'filament.pages.auth.edit-profile';

    public static function registerRoutes(Panel $panel): void
    {
        self::routes($panel);
    }

    public static function getLabel(): string
    {
        return __('Profile');
    }

    public function getTitle(): string|Htmlable
    {
        return self::getLabel();
    }
}
