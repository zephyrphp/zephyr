<?php

declare(strict_types=1);

use App\Filament\Pages\Auth\EditProfile;
use App\Livewire\Forms\Profile\DeleteUserForm;
use App\Livewire\Forms\Profile\UpdatePasswordForm;
use App\Livewire\Forms\Profile\UpdateProfileInformationForm;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('app'),
    );

    Filament::auth()->setUser(User::factory()->create());
});

it('displays profile page', function (): void {
    Livewire::test(EditProfile::class)
        ->assertSuccessful();
});

it('can update profile', function (): void {
    $updatedUser = User::factory()->make();

    Livewire::test(UpdateProfileInformationForm::class)
        ->fillForm([
            'name' => $updatedUser->name,
            'email' => $updatedUser->email,
        ])
        ->call('updateUserProfileInformation')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $updatedUser->name,
        'email' => $updatedUser->email,
    ]);
});

it('can validate required', function ($column): void {
    Livewire::test(UpdateProfileInformationForm::class)
        ->fillForm([$column => null])
        ->call('updateUserProfileInformation')
        ->assertHasFormErrors([$column => ['required']]);
})->with(['name', 'email']);

it('can validate email', function (): void {
    Livewire::test(UpdateProfileInformationForm::class)
        ->fillForm([
            'email' => 'invalid-email-format',
        ])
        ->call('updateUserProfileInformation')
        ->assertHasFormErrors(['email' => ['email']]);
});

it('can validate unique email', function (): void {
    $otherUser = User::factory()->create();

    Livewire::test(UpdateProfileInformationForm::class)
        ->fillForm([
            'email' => $otherUser->email,
        ])
        ->call('updateUserProfileInformation')
        ->assertHasFormErrors(['email' => ['unique']]);
});

it('can validate max length', function (string $column): void {
    Livewire::test(UpdateProfileInformationForm::class)
        ->fillForm([$column => Str::random(256)])
        ->call('updateUserProfileInformation')
        ->assertHasFormErrors([$column => ['max:255']]);
})->with(['name', 'email']);

it('can update password', function (): void {
    Livewire::test(UpdatePasswordForm::class)
        ->fillForm([
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->call('updatePassword')
        ->assertHasNoFormErrors();

    $this->assertTrue(Hash::check('new-password', Filament::auth()->user()->fresh()->password));
});

it('can validate current password', function (): void {
    Livewire::test(UpdatePasswordForm::class)
        ->fillForm([
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->call('updatePassword')
        ->assertHasFormErrors(['current_password']);
});

it('can validate password confirmation', function (): void {
    Livewire::test(UpdatePasswordForm::class)
        ->fillForm([
            'current_password' => 'password',
            'password' => 'password',
            'password_confirmation' => 'different-password',
        ])
        ->call('updatePassword')
        ->assertHasFormErrors(['password' => ['same']]);
});

it('can destroy account', function (): void {
    $user = Filament::auth()->user();

    Livewire::test(DeleteUserForm::class)
        ->assertActionExists('deleteAction')
        ->callAction('deleteAction', [
            'password' => 'password',
        ])
        ->assertHasNoActionErrors();

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

it('can provide password to delete account', function (): void {
    Livewire::test(DeleteUserForm::class)
        ->callAction('deleteAction', [
            'password' => 'wrong-password',
        ])
        ->assertHasActionErrors(['password']);
});
