<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\VariantProduct;
use App\Models\InventoryIn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;

final class InventoryInReportTable extends BaseDataTable
{
    public $dateStart;

    public $dateEnd;

    public function builder(): Builder
    {
        $this->dateStart = Carbon::parse($this->dateStart)->startOfDay();
        $this->dateEnd = Carbon::parse($this->dateEnd)->endOfDay();

        return InventoryIn::query()
            ->whereBetween('transaction_date', [$this->dateStart, $this->dateEnd])
            ->with('product');
    }

    public function configure(): void
    {
        $this
            ->setPrimaryKey('id')
            ->setQueryStringStatus(true)
            ->setQueryStringStatusForSearch(false)
            ->setQueryStringStatusForSort(false)
            ->setQueryStringStatusForFilter(false)
            ->setTableWrapperAttributes([
                'class' => 'table-responsive text-nowrap',
            ])
            ->setTableAttributes([
                'class' => 'table table-striped',
            ])
            ->setTrAttributes(fn () => ['default' => false])
            ->setTdAttributes(fn () => ['default' => true])
            ->setColumnSelectDisabled()
            ->storeFiltersInSessionDisabled()
            ->setToolBarAttributes([
                'class' => 'mt-1',
                'default-colors' => true,
                'default-styling' => true,
            ])
            ->setPerPageAccepted([5, 10, 25, 50, 100, 200, -1])
            ->setPerPage(25)
            ->setDefaultPerPage(25);
    }

    public function mount($dateStart, $dateEnd): void
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true),
            DateColumn::make('Transaction date', 'transaction_date')
                ->inputFormat('Y-m-d H:i:s')
                ->outputFormat('l, d F Y')
                ->sortable(),
            Column::make('Batch code', 'batch_code')
                ->sortable()
                ->searchable(),
            Column::make('Product Name', 'product.name')
                ->sortable()
                ->searchable(),
            Column::make('Variant', 'product.variant')
                ->format(fn ($value) => VariantProduct::tryFrom($value)?->label() ?? 'Unknown')
                ->sortable(),
            Column::make('Shelf name', 'shelf_name')
                ->sortable()
                ->searchable(),
            Column::make('Stock start', 'stock_start')
                ->sortable(),
            Column::make('Current stock', 'current_stock')
                ->sortable(),
            DateColumn::make('Expiration date', 'expiration_date')
                ->inputFormat('Y-m-d H:i:s')
                ->outputFormat('l, d F Y')
                ->sortable(),
        ];
    }
}
