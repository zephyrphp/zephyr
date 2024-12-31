<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Profile;

use Exception;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

final class UpdatePasswordForm extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getCurrentPasswordFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('data');
    }

    /**
     * @throws Exception
     */
    public function getUser(): Authenticatable&Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render(): View
    {
        return view('livewire.forms.profile.update-password-form');
    }

    public function submitAction(): Action
    {
        return Action::make('submit')
            ->label(__('Save'))
            ->submit('updatePassword');
    }

    /**
     * @throws ValidationException
     */
    public function updatePassword(): void
    {
        $data = $this->form->getState();

        $this->getUser()->update([
            'password' => Hash::make($data['password']),
        ]);

        Notification::make()
            ->success()
            ->title(__('Password updated'))
            ->send();
    }

    private function getCurrentPasswordFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('current_password')
            ->currentPassword()
            ->label(__('Current Password'))
            ->maxLength(255)
            ->password()
            ->required();
    }

    private function getPasswordFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('password')
            ->label(__('Password'))
            ->maxLength(255)
            ->password()
            ->rule(Password::default())
            ->required()
            ->same('password_confirmation');
    }

    private function getPasswordConfirmationFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('password_confirmation')
            ->label(__('Confirm Password'))
            ->maxLength(255)
            ->password()
            ->required();
    }
}
