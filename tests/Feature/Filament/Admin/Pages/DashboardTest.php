<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('displays dashboard page', function (): void {
    $this
        ->actingAs(User::factory()->admin()->create())
        ->get(Dashboard::getUrl())
        ->assertSuccessful();
});

it('checks admin user role', function (): void {
    $this
        ->actingAs(User::factory()->create())
        ->get(Dashboard::getUrl())
        ->assertForbidden();
});
