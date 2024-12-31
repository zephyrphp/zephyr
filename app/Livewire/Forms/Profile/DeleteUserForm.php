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
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Component;

final class DeleteUserForm extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->action(function (): void {
                $user = $this->getUser();

                Auth::logout();

                $user->delete();

                Session::invalidate();
                Session::regenerateToken();

                $this->redirect('/', navigate: true);
            })
            ->color('danger')
            ->form([
                Forms\Components\TextInput::make('password')
                    ->currentPassword()
                    ->label(__('Current Password'))
                    ->maxLength(255)
                    ->password()
                    ->required(),
            ])
            ->label(__('Delete Account'))
            ->requiresConfirmation();
    }

    /**
     * @throws Exception
     */
    public function getUser(): Authenticatable|Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    public function render(): View
    {
        return view('livewire.forms.profile.delete-user-form');
    }
}
