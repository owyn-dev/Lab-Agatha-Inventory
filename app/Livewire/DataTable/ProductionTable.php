<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\StatusProduction;
use App\Models\Production;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

final class ProductionTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return Production::query()
            ->with('rejectedBy.roles')
            ->where('status', '!=', StatusProduction::WAITING_FOR_RESPONSE)
            ->orderByRaw('FIELD(status, ?, ?, ?)', [
                StatusProduction::IN_PROGRESS->value,
                StatusProduction::APPROVED->value,
                StatusProduction::REJECTED->value,
            ])
            ->orderBy('production_request_date', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->format(fn (string $value) => 'PR-' . mb_str_pad($value, 5, '0', STR_PAD_LEFT))
                ->sortable(),
            Column::make('Production Request From', 'inventoryUser.full_name')
                ->sortable()
                ->searchable(),
            DateColumn::make('Production Request Date', 'production_request_date')
                ->inputFormat('Y-m-d H:i:s')
                ->outputFormat('l, d F Y')
                ->sortable(),
            Column::make('Production Handled By', 'productionUser.full_name')
                ->format(fn ($value) => $value ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Production Date', 'production_date')
                ->format(fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('l, d F Y') : 'N/A')
                ->sortable(),
            Column::make('Status', 'status')
                ->format(fn ($value) => '<span class="badge ' . $value->getBadgeClass() . '">' . $value->label() . '</span>')
                ->sortable()
                ->html(),
            Column::make('#', 'rejected_by')
                ->hideIf(true),
            ButtonGroupColumn::make('Actions')
                ->attributes(fn () => [
                    'class' => 'space-x-2',
                ])
                ->buttons([
                    LinkColumn::make('Show')
                        ->title(fn () => '<i class="bi bi-eye"></i>')
                        ->location(fn ($row) => route('production.show', $row->id))
                        ->attributes(fn () => [
                            'class' => Auth::user()->can('show_production') ? 'btn icon btn-sm btn-info' : 'btn icon btn-sm btn-info d-none',
                        ])->html(),
                    LinkColumn::make('Edit')
                        ->title(fn () => '<i class="bi bi-pencil"></i>')
                        ->location(function ($row) {
                            $status = $row->status;
                            $user = Auth::user();
                            $isAdmin = $user->hasRole('administrator');

                            $hasSameRole = false;
                            if (StatusProduction::REJECTED === $status && $row->rejectedBy) {
                                $loginRoles = $user->roles->pluck('name');
                                $rejectedRoles = $row->rejectedBy->roles->pluck('name');
                                $hasSameRole = $loginRoles->intersect($rejectedRoles)->isNotEmpty();
                            }

                            $canEdit = (
                                StatusProduction::IN_PROGRESS === $status ||
                                (StatusProduction::REJECTED === $status && ($hasSameRole || $isAdmin))
                            );

                            return $canEdit ? route('production.edit', $row->id) : '#';
                        })
                        ->attributes(function ($row) {
                            $status = $row->status;
                            $user = Auth::user();
                            $isAdmin = $user->hasRole('administrator');

                            $hasSameRole = false;
                            if (StatusProduction::REJECTED === $status && $row->rejectedBy) {
                                $loginRoles = $user->roles->pluck('name');
                                $rejectedRoles = $row->rejectedBy->roles->pluck('name');
                                $hasSameRole = $loginRoles->intersect($rejectedRoles)->isNotEmpty();
                            }

                            $canEdit = $user->can('edit_production') && (
                                StatusProduction::IN_PROGRESS === $status ||
                                (StatusProduction::REJECTED === $status && ($hasSameRole || $isAdmin))
                            );

                            return [
                                'class' => $canEdit
                                    ? 'btn icon btn-sm btn-warning'
                                    : 'btn icon btn-sm btn-warning d-none',
                            ];
                        })
                        ->html(),
                ]),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    ...collect(StatusProduction::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])->toArray(),
                ])
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('status', $value);
                }),
            DateFilter::make('Request Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('production_request_date', '>=', $value);
                }),
            DateFilter::make('Request Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('production_request_date', '<=', $value);
                }),
            DateFilter::make('Production Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('production_date', '>=', $value);
                }),
            DateFilter::make('Production Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('production_date', '<=', $value);
                }),
        ];
    }
}
