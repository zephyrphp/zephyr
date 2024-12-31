<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Profile;

use App\Models\User;
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
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

final class UpdateProfileInformationForm extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
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
        $this->form->fill([
            'name' => $this->getUser()->name,
            'email' => $this->getUser()->email,
        ]);
    }

    public function render(): View
    {
        return view('livewire.forms.profile.update-profile-information-form');
    }

    public function submitAction(): Action
    {
        return Action::make('submit')
            ->label(__('Save'))
            ->submit('updateUserProfileInformation');
    }

    /**
     * @throws ValidationException
     */
    public function updateUserProfileInformation(): void
    {
        $data = $this->form->getState();

        $this->getUser()->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if ($this->getUser()->isDirty('email')) {
            $this->getUser()->email_verified_at = null;
        }

        $this->getUser()->save();

        Notification::make()
            ->success()
            ->title(__('Profile updated'))
            ->send();
    }

    private function getEmailFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('email')
            ->email()
            ->label(__('Email'))
            ->maxLength(255)
            ->required()
            ->unique(User::class, ignorable: $this->getUser());
    }

    private function getNameFormComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('Name'))
            ->maxLength(255)
            ->required();
    }
}
