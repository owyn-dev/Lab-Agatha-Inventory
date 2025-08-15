<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\StatusProductRequest;
use App\Models\ProductRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

final class InventoryProductRequestsTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return ProductRequest::query()
            ->orderByRaw('FIELD(status, ?, ?, ?, ?)', [
                StatusProductRequest::WAITING_FOR_RESPONSE->value,
                StatusProductRequest::IN_PRODUCTION->value,
                StatusProductRequest::APPROVED->value,
                StatusProductRequest::REJECTED->value,
            ])
            ->orderBy('product_request_date', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true),
            Column::make('Product Request From', 'salesUser.full_name')
                ->sortable()
                ->searchable(),
            DateColumn::make('Product Request Date', 'product_request_date')
                ->inputFormat('Y-m-d H:i:s')
                ->outputFormat('l, d F Y')
                ->sortable(),
            Column::make('Handled By', 'handledBy.full_name')
                ->format(fn ($value) => $value ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Handled Date', 'handled_date')
                ->format(fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('l, d F Y') : 'N/A')
                ->sortable(),
            Column::make('Status', 'status')
                ->format(fn ($value) => '<span class="badge ' . $value->getBadgeClass() . '">' . $value->label() . '</span>')
                ->sortable()
                ->html(),
            ButtonGroupColumn::make('Actions')
                ->attributes(fn () => ['default' => true])
                ->buttons([
                    LinkColumn::make('Show')
                        ->title(fn () => '<i class="bi bi-eye"></i>')
                        ->location(fn ($row) => StatusProductRequest::WAITING_FOR_RESPONSE === $row->status ? '#' : route('inventory.request.product.show', $row->id))
                        ->attributes(fn ($row) => [
                            'class' => Auth::user()->can('show_inventory_product_request') && StatusProductRequest::WAITING_FOR_RESPONSE !== $row->status
                                ? 'btn icon btn-sm btn-info'
                                : 'btn icon btn-sm btn-info d-none',
                        ])->html(),
                    LinkColumn::make('Edit')
                        ->title(fn () => '<i class="bi bi-pencil"></i>')
                        ->location(
                            fn ($row) => in_array($row->status, [
                                StatusProductRequest::WAITING_FOR_RESPONSE,
                                StatusProductRequest::IN_PRODUCTION,
                            ])
                                ? route('inventory.request.product.edit', $row->id)
                                : '#'
                        )
                        ->attributes(fn ($row) => [
                            'class' => Auth::user()->can('edit_inventory_product_request') &&
                                       in_array($row->status, [
                                           StatusProductRequest::WAITING_FOR_RESPONSE,
                                           StatusProductRequest::IN_PRODUCTION,
                                       ])
                                ? 'btn icon btn-sm btn-warning'
                                : 'btn icon btn-sm btn-warning d-none',
                        ])
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
                    ...collect(StatusProductRequest::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])->toArray(),
                ])
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('status', $value);
                }),
            DateFilter::make('Request Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('product_request_date', '>=', $value);
                }),
            DateFilter::make('Request Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('product_request_date', '<=', $value);
                }),
            DateFilter::make('Handled Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('handled_date', '>=', $value);
                }),
            DateFilter::make('Handled Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('handled_date', '<=', $value);
                }),
        ];
    }
}
