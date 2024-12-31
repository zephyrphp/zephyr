<?php

declare(strict_types=1);

use App\Filament\Pages\Auth\Login;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('app'),
    );
});

test('an unauthenticated user can access the login page', function (): void {
    $this
        ->get(Filament::getLoginUrl())
        ->assertOk();
});

test('an unauthenticated user can not access the app panel', function (): void {
    $this
        ->get('app')
        ->assertRedirect(Filament::getLoginUrl());
});

test('an unauthenticated user can login', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertActionExists('authenticate')
        ->call('authenticate')
        ->assertHasNoActionErrors();

    $this->assertAuthenticated();
});

test('an authenticated user can access the app panel', function (): void {
    $this
        ->actingAs(User::factory()->create())
        ->get('app')
        ->assertOk();
});

test('an authenticated user can logout', function (): void {
    $this
        ->actingAs(User::factory()->create())
        ->assertAuthenticated();

    $this
        ->post(Filament::getLogoutUrl())
        ->assertRedirect('/');
});
