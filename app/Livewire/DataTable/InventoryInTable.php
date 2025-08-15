<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\VariantProduct;
use App\Models\InventoryIn;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

final class InventoryInTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return InventoryIn::query()
            ->where('wasted', 'No')
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
            Column::make('Batch Code', 'batch_code')
                ->sortable()
                ->searchable(),
            Column::make('Product Name', 'product.name')
                ->sortable()
                ->searchable(),
            Column::make('Variant', 'product.variant')
                ->format(fn ($value) => VariantProduct::getNameByValue($value))
                ->sortable(),
            Column::make('Shelf name', 'shelf_name')
                ->sortable(),
            Column::make('Stock start', 'stock_start')
                ->sortable(),
            Column::make('Current stock', 'current_stock')
                ->sortable(),
            Column::make('Unit price', 'unit_price')
                ->format(fn (int $value) => 'Rp ' . number_format($value, 0, ',', '.'))
                ->sortable()
                ->hideIf(true),
            Column::make('Expiration date', 'expiration_date')
                ->format(function ($value, $row) {
                    $date = \Carbon\Carbon::parse($value);
                    $formatted = $date->translatedFormat('l, d F Y');

                    if ($date->isPast() && $row->current_stock > 0) {
                        return '<span style="color: red;">' . $formatted . '</span>';
                    }

                    return $formatted;
                })
                ->html()
                ->sortable(),
            Column::make('#', 'wasted')
                ->hideIf(true),
            ButtonGroupColumn::make('Actions')
                ->attributes(fn () => ['default' => true])
                ->buttons([
                    LinkColumn::make('Delete')
                        ->title(fn () => '<i class="bi bi-trash"></i>')
                        ->location(fn ($row) => \Carbon\Carbon::parse($row->expiration_date)->isPast() && $row->stock > 0 && 'No' === $row->wasted ? '#title-datatable' : '#')
                        ->attributes(fn ($row) => [
                            'wire:click' => 'changeData(' . $row->id . ')',
                            'class' => Auth::user()->can('trash_inventory_in') && \Carbon\Carbon::parse($row->expiration_date)->isPast() && $row->current_stock > 0 && 'No' === $row->wasted
                                ? 'btn icon btn-sm btn-danger'
                                : 'btn icon btn-sm btn-danger d-none',
                        ])->html(),
                ]),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Stock')
                ->options([
                    '' => 'All',
                    'Non Zero' => 'Stock > 0',
                ])
                ->filter(function ($builder, $value) {
                    if ('Non Zero' === $value) {
                        $builder->where('current_stock', '>', 0);
                    }

                    return $builder;
                }),
            SelectFilter::make('Variant Product')
                ->options([
                    '' => 'All',
                    ...collect(VariantProduct::cases())->mapWithKeys(fn ($variant) => [$variant->value => $variant->label()])->toArray(),
                ])
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('variant', $value);
                }),
            DateFilter::make('Transaction From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('transaction_date', '>=', $value);
                }),
            DateFilter::make('Transaction To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('transaction_date', '<=', $value);
                }),
            DateFilter::make('Expiration Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('expiration_date', '>=', $value);
                }),
            DateFilter::make('Expiration Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('expiration_date', '<=', $value);
                }),
        ];
    }

    public function changeData($id): void
    {
        if ( ! Auth::user()->can('trash_inventory_in')) {
            abort(403, 'User does not have the right permissions.');
        }

        sweetalert()
            ->options(['id' => $id])
            ->showDenyButton()
            ->info('Are you sure you want to throw away this expired item?');
    }

    #[On('sweetalert:confirmed')]
    public function onConfirmed(array $payload): void
    {
        $id = $payload['envelope']['options']['id'];

        DB::beginTransaction();

        try {
            $model = InventoryIn::findOrFail($id);

            $product = $model->product;

            if ( ! $product) {
                throw new Exception('Product not found for this inventory item.');
            }

            $product->stock -= $model->current_stock;
            if ($product->stock < 0) {
                $product->stock = 0;
            }
            $product->save();

            $model->wasted = 'Yes';
            $model->save();

            DB::commit();

            $this->resetPage();
            flash()->info('Data successfully changed.');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            flash()->warning('Data not found.');
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error('Failed to change data. ' . $e->getMessage());
        }
    }
}
