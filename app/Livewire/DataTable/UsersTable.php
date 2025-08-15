<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\UserRole;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

final class UsersTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return User::query();
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true),
            Column::make('Full Name', 'full_name')
                ->sortable()
                ->searchable(),
            Column::make('Username', 'username')
                ->sortable()
                ->searchable(),
            Column::make('Role', 'modelHasRoles.role.name')
                ->sortable()
                ->format(fn ($value) => UserRole::tryFrom($value) ? '<span class="badge ' . UserRole::from($value)->getBadgeClass() . '">' . UserRole::from($value)->label() . '</span>' : '-')
                ->html(),
            DateColumn::make('Created At', 'created_at')
                ->outputFormat('l, d F Y')
                ->sortable(),
            DateColumn::make('Updated At', 'updated_at')
                ->outputFormat('l, d F Y')
                ->sortable(),

            ButtonGroupColumn::make('Actions')
                ->attributes(fn () => [
                    'class' => 'space-x-2',
                ])
                ->buttons([
                    LinkColumn::make('Show')
                        ->title(fn () => '<i class="bi bi-eye"></i>')
                        ->location(fn ($row) => route('user.show', $row->id))
                        ->attributes(fn () => [
                            'class' => Auth::user()->can('show_user') ? 'btn icon btn-sm btn-info' : 'btn icon btn-sm btn-info d-none',
                        ])->html(),
                    LinkColumn::make('Edit')
                        ->title(fn () => '<i class="bi bi-pencil"></i>')
                        ->location(fn ($row) => route('user.edit', $row->id))
                        ->attributes(fn () => [
                            'class' => Auth::user()->can('edit_user') ? 'btn icon btn-sm btn-warning' : 'btn icon btn-sm btn-warning d-none',
                        ])->html(),
                    LinkColumn::make('Delete')
                        ->title(fn () => '<i class="bi bi-trash"></i>')
                        ->location(fn () => '#title-datatable')
                        ->attributes(fn ($row) => [
                            'wire:click' => 'delete(' . $row->id . ')',
                            'class' => Auth::user()->can('delete_user') ? 'btn icon btn-sm btn-danger' : 'btn icon btn-sm btn-danger d-none',
                        ])->html(),
                ]),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Role')
                ->options([
                    '' => 'All',
                    ...collect(UserRole::cases())->mapWithKeys(fn ($role) => [$role->value => $role->label()])->toArray(),
                ])
                ->filter(function (Builder $builder, string $value): void {
                    if ($role = UserRole::tryFrom($value)) {
                        $builder->whereHas('modelHasRoles.role', fn ($q) => $q->where('name', $role->value));
                    }
                }),

            DateFilter::make('Updated Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('users.updated_at', '>=', $value);
                }),
            DateFilter::make('Updated Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('users.updated_at', '<=', $value);
                }),
        ];
    }

    public function delete($id): void
    {
        if ( ! Auth::user()->can('delete_user')) {
            abort(403, 'User does not have the right permissions.');
        }

        sweetalert()
            ->options(['id' => $id])
            ->showDenyButton()
            ->info('Are you sure you want to delete this item ?');
    }

    #[On('sweetalert:confirmed')]
    public function onConfirmed(array $payload): void
    {
        $id = $payload['envelope']['options']['id'];

        try {
            $model = User::findOrFail($id);
            $model->delete();

            $this->resetPage();

            flash()->info('Data successfully deleted.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            flash()->warning('Data not found.');
        } catch (Exception $e) {
            flash()->error('Failed to delete data.');
        }
    }
}
