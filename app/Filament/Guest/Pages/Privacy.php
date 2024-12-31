<?php

declare(strict_types=1);

namespace App\Filament\Guest\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

final class Privacy extends Page
{
    protected static string $view = 'filament.guest.pages.privacy';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = '/privacy-policy';

    public static function getNavigationLabel(): string
    {
        return __('Privacy Policy');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Privacy Policy');
    }
}
