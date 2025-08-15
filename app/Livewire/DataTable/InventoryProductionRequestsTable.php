<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\StatusProduction;
use App\Models\InventoryIn;
use App\Models\Production;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

final class InventoryProductionRequestsTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return Production::query()
            ->with('rejectedBy.roles')
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
                ->attributes(fn () => ['default' => true])
                ->buttons([
                    LinkColumn::make('Show')
                        ->title(fn () => '<i class="bi bi-eye"></i>')
                        ->location(fn ($row) => StatusProduction::WAITING_FOR_RESPONSE === $row->status ? '#' : route('inventory.request.show', $row->id))
                        ->attributes(fn ($row) => [
                            'class' => Auth::user()->can('show_inventory_production_request') && StatusProduction::WAITING_FOR_RESPONSE !== $row->status
                                ? 'btn icon btn-sm btn-info'
                                : 'btn icon btn-sm btn-info d-none',
                        ])->html(),
                    LinkColumn::make('Edit')
                        ->title(fn () => '<i class="bi bi-pencil"></i>')
                        ->location(fn ($row) => StatusProduction::WAITING_FOR_RESPONSE === $row->status ? route('inventory.request.edit', $row->id) : '#')
                        ->attributes(fn ($row) => [
                            'class' => Auth::user()->can('edit_inventory_production_request') && StatusProduction::WAITING_FOR_RESPONSE === $row->status
                                ? 'btn icon btn-sm btn-warning'
                                : 'btn icon btn-sm btn-warning d-none',
                        ])->html(),
                    LinkColumn::make('Delete')
                        ->title(fn () => '<i class="bi bi-trash"></i>')
                        ->location(fn ($row) => StatusProduction::WAITING_FOR_RESPONSE === $row->status ? '#title-datatable' : '#')
                        ->attributes(fn ($row) => [
                            'wire:click' => 'deleteData(' . $row->id . ')',
                            'class' => Auth::user()->can('delete_inventory_production_request') && StatusProduction::WAITING_FOR_RESPONSE === $row->status
                                ? 'btn icon btn-sm btn-danger'
                                : 'btn icon btn-sm btn-danger d-none',
                        ])->html(),
                    LinkColumn::make('Accepted')
                        ->title(fn () => '<i class="bi bi-check2-all"></i>')
                        ->location(fn () => '#')
                        ->attributes(fn ($row) => [
                            'wire:click' => StatusProduction::PENDING_APPROVAL === $row->status ? 'acceptedData(' . $row->id . ')' : '',
                            'class' => Auth::user()->can('edit_inventory_production_request') && StatusProduction::PENDING_APPROVAL === $row->status
                                ? 'btn icon btn-sm btn-success'
                                : 'btn icon btn-sm btn-success d-none',
                        ])->html(),
                    LinkColumn::make('Rejected')
                        ->title(fn () => '<i class="bi bi-x-octagon"></i>')
                        ->location(fn ($row) => StatusProduction::PENDING_APPROVAL === $row->status ? route('inventory.request.edit-status', $row->id) : '#')
                        ->attributes(fn ($row) => [
                            'class' => Auth::user()->can('edit_inventory_production_request') && StatusProduction::PENDING_APPROVAL === $row->status
                                ? 'btn icon btn-sm btn-danger'
                                : 'btn icon btn-sm btn-danger d-none',
                        ])->html(),
                    LinkColumn::make('Rollback')
                        ->title(fn () => '<i class="bi bi-arrow-counterclockwise"></i>')
                        ->location(fn () => '#')
                        ->attributes(function ($row) {
                            $user = Auth::user();
                            $status = $row->status;
                            $isVisible = false;

                            if (StatusProduction::REJECTED === $status && $row->rejectedBy) {
                                $loginRoles = $user->roles->pluck('name');
                                $rejectedRoles = $row->rejectedBy->roles->pluck('name');
                                $hasSameRole = $loginRoles->intersect($rejectedRoles)->isNotEmpty();

                                $isVisible = $hasSameRole || $user->hasRole('administrator');
                            }

                            return [
                                'class' => $isVisible
                                    ? 'btn icon btn-sm btn-danger'
                                    : 'btn icon btn-sm btn-danger d-none',
                                'wire:click' => $isVisible ? 'rollback(' . $row->id . ')' : '',
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

    public function acceptedData($id)
    {
        $production = Production::with('detailProduction.product')->findOrFail($id);

        if ( ! $production) {
            return flash()->warning('Data not found.');
        }

        try {
            DB::transaction(function () use ($production): void {
                $production->update(['status' => StatusProduction::APPROVED]);

                $productionDate = \Carbon\Carbon::parse($production->production_date);
                $transactionDate = $productionDate->format('Y-m-d');

                foreach ($production->detailProduction as $detail) {
                    $expirationDate = $productionDate->copy()
                        ->addDays($detail->product->expired_day)
                        ->format('Y-m-d');

                    $inventory = InventoryIn::create([
                        'product_id' => $detail->product_id,
                        'batch_code' => $detail->batch_code,
                        'transaction_date' => $transactionDate,
                        'shelf_name' => $detail->shelf_name,
                        'stock_start' => $detail->quantity,
                        'current_stock' => $detail->quantity,
                        'unit_price' => $detail->product->price,
                        'expiration_date' => $expirationDate,
                    ]);

                    if ( ! $inventory) {
                        throw new Exception('Failed to create data inventory');
                    }

                    $detail->product->increment('stock', $detail->quantity);
                }
            });
        } catch (Exception $e) {
            return flash()->error('Failed to change data.' . $e);
        }

        return flash()->info('Data updated successfully.');
    }

    public function deleteData($id): void
    {
        if ( ! Auth::user()->can('delete_inventory_production_request')) {
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
            $model = Production::findOrFail($id);
            $model->delete();

            $this->resetPage();

            flash()->info('Data successfully deleted.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            flash()->warning('Data not found.');
        } catch (Exception $e) {
            flash()->error('Failed to delete data.');
        }
    }

    public function rollback(int $id): void
    {
        $user = Auth::user();

        $production = Production::with('rejectedBy.roles')->findOrFail($id);

        if (StatusProduction::REJECTED !== $production->status || ! $production->rejectedBy) {
            abort(403, 'Rollback only allowed for rejected productions.');
        }

        $loginRoles = $user->roles->pluck('name');
        $rejectedRoles = $production->rejectedBy->roles->pluck('name');
        $hasSameRole = $loginRoles->intersect($rejectedRoles)->isNotEmpty();

        if ( ! $hasSameRole && ! $user->hasRole('administrator')) {
            abort(403, 'You are not authorized to rollback this production.');
        }

        $production->update([
            'status' => StatusProduction::PENDING_APPROVAL,
            'rejected_by' => null,
            'rejected_at' => null,
        ]);

        $this->dispatch('refreshComponent');
        flash()->success('Production successfully rolled back.');
    }
}
