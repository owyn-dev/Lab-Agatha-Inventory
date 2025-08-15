<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\VariantProduct;
use App\Models\InventoryOut;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

final class InventoryOutTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return InventoryOut::query()
            ->orderBy('transaction_date', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true),
            DateColumn::make('Transaction Date', 'transaction_date')
                ->inputFormat('Y-m-d H:i:s')
                ->outputFormat('l, d F Y')
                ->sortable(),
            Column::make('Batch code', 'batch_code')
                ->sortable()
                ->searchable(),
            Column::make('Product Name', 'inventoryIn.product.name')
                ->sortable()
                ->searchable(),
            Column::make('Variant', 'inventoryIn.product.variant')
                ->format(fn ($value) => VariantProduct::getNameByValue($value))
                ->sortable()
                ->searchable(),
            Column::make('Unit price', 'inventoryIn.unit_price')
                ->format(fn (int $value) => 'Rp ' . number_format($value, 0, ',', '.'))
                ->sortable(),
            Column::make('Shelf name', 'shelf_name')
                ->sortable(),
            Column::make('Stock out', 'stock_out')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Transaction From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('inventory_out.transaction_date', '>=', $value);
                }),
            DateFilter::make('Transaction To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('inventory_out.transaction_date', '<=', $value);
                }),
        ];
    }
}
