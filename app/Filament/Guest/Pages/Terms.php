<?php

declare(strict_types=1);

namespace App\Filament\Guest\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

final class Terms extends Page
{
    protected static string $view = 'filament.guest.pages.terms';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = '/terms-of-service';

    public static function getNavigationLabel(): string
    {
        return __('Terms of Service');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Terms of Service');
    }
}
