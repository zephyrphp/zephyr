<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\UserResource\Pages\ManageUsers;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Str;
use Livewire\Livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('can render the index page', function (): void {
    Livewire::test(ManageUsers::class)
        ->assertSuccessful();
});

it('has column', function (string $column): void {
    Livewire::test(ManageUsers::class)
        ->assertTableColumnExists($column);
})->with(['name', 'email', 'email_verified_at', 'is_admin', 'created_at', 'updated_at']);

it('can render column', function (string $column): void {
    Livewire::test(ManageUsers::class)
        ->assertCanRenderTableColumn($column);
})->with(['name', 'email', 'email_verified_at', 'is_admin', 'created_at', 'updated_at']);

it('can sort column', function (string $column): void {
    $records = User::factory(5)->create();

    Livewire::test(ManageUsers::class)
        ->sortTable($column)
        ->assertCanSeeTableRecords($records->sortBy($column), inOrder: true)
        ->sortTable($column, 'desc')
        ->assertCanSeeTableRecords($records->sortByDesc($column), inOrder: true);
})->with(['name', 'email', 'email_verified_at', 'created_at', 'updated_at']);

it('can search column', function (string $column): void {
    $records = User::factory(5)->create();

    $value = $records->first()->{$column};

    Livewire::test(ManageUsers::class)
        ->searchTable($value)
        ->assertCanSeeTableRecords($records->where($column, $value))
        ->assertCanNotSeeTableRecords($records->where($column, '!=', $value));
})->with(['name', 'email']);

it('can create a record', function (): void {
    $record = User::factory()->make();

    Livewire::test(ManageUsers::class)
        ->assertActionExists('create')
        ->callAction('create', [
            'name' => $record->name,
            'email' => $record->email,
            'password' => $record->password,
            'password_confirmation' => $record->password,
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $record->name,
        'email' => $record->email,
    ]);
});

it('can update a record', function (): void {
    $record = User::factory()->create();
    $newRecord = User::factory()->make();

    Livewire::test(ManageUsers::class)
        ->assertTableActionExists('edit')
        ->callTableAction('edit', $record, [
            'name' => $newRecord->name,
            'email' => $newRecord->email,
        ])
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $newRecord->name,
        'email' => $newRecord->email,
    ]);
});

it('can delete a record', function (): void {
    $record = User::factory()->create();

    Livewire::test(ManageUsers::class)
        ->assertTableActionExists('delete')
        ->callTableAction(DeleteAction::class, $record);

    $this->assertModelMissing($record);
});

it('can bulk delete records', function (): void {
    $records = User::factory(5)->create();

    Livewire::test(ManageUsers::class)
        ->assertTableBulkActionExists('delete')
        ->callTableBulkAction(DeleteBulkAction::class, $records);

    foreach ($records as $record) {
        $this->assertModelMissing($record);
    }
});

it('can validate required', function (string $column): void {
    Livewire::test(ManageUsers::class)
        ->assertActionExists('create')
        ->callAction('create', [$column => null])
        ->assertHasActionErrors([$column => ['required']]);
})->with(['name', 'email']);

it('can validate unique', function (string $column): void {
    $record = User::factory()->create();

    Livewire::test(ManageUsers::class)
        ->assertActionExists('create')
        ->callAction('create', ['email' => $record->email])
        ->assertHasActionErrors([$column => ['unique']]);
})->with(['email']);

it('can validate email', function (string $column): void {
    Livewire::test(ManageUsers::class)
        ->assertActionExists('create')
        ->callAction('create', ['email' => Str::random()])
        ->assertHasActionErrors([$column => ['email']]);
})->with(['email']);

it('can validate max length', function (string $column): void {
    Livewire::test(ManageUsers::class)
        ->assertActionExists('create')
        ->callAction('create', [$column => Str::random(256)])
        ->assertHasActionErrors([$column => ['max:255']]);
})->with(['name', 'email']);

it('can validate password confirmation', function (): void {
    $record = User::factory()->make();

    Livewire::test(ManageUsers::class)
        ->assertActionExists('create')
        ->callAction('create', [
            'password' => $record->password,
            'password_confirmation' => Str::random(),
        ])
        ->assertHasActionErrors(['password' => ['same']]);
});
