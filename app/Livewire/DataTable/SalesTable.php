<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;

final class SalesTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return Sale::query()
            ->orderBy('transaction_date', 'desc')
            ->withCount('detailSale');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->format(fn (string $value) => 'SA-' . mb_str_pad($value, 5, '0', STR_PAD_LEFT))
                ->sortable(),
            Column::make('Logged By', 'salesUser.full_name')
                ->sortable()
                ->searchable(),
            DateColumn::make('Transaction Date', 'transaction_date')
                ->inputFormat('Y-m-d H:i:s')
                ->outputFormat('l, d F Y')
                ->sortable(),
            Column::make('Products List')->label(fn ($row) => $row->detail_sale_count . ' Products'),
            Column::make('Total Amount', 'total_amount')
                ->format(fn (int $value) => 'Rp ' . number_format($value, 0, ',', '.'))
                ->sortable(),
            ButtonGroupColumn::make('Actions')
                ->attributes(fn () => [
                    'class' => 'space-x-2',
                ])
                ->buttons([
                    LinkColumn::make('Show')
                        ->title(fn () => '<i class="bi bi-eye"></i>')
                        ->location(fn ($row) => route('sales.show', $row->id))
                        ->attributes(fn () => [
                            'class' => Auth::user()->can('show_sale') ? 'btn icon btn-sm btn-info' : 'btn icon btn-sm btn-info d-none',
                        ])->html(),
                ]),
        ];
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Transaction Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('transaction_date', '>=', $value);
                }),
            DateFilter::make('Transaction Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('transaction_date', '<=', $value);
                }),
        ];
    }
}
