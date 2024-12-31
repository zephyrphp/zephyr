<?php

declare(strict_types=1);

namespace App\Filament\Guest\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

final class Home extends Page
{
    protected static string $view = 'filament.guest.pages.home';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = '/';

    public static function getNavigationLabel(): string
    {
        return __('Home');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Home');
    }
}
