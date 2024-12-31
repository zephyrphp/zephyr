<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('app'),
    );
});

it('displays dashboard page', function (): void {
    $this
        ->actingAs(User::factory()->create())
        ->get(Dashboard::getUrl())
        ->assertSuccessful();
});
