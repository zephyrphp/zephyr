<?php

declare(strict_types=1);

use Filament\Facades\Filament;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('guest'),
    );
});

it('displays home page', function (): void {
    $response = $this->get('/');

    $response->assertOk();
});
