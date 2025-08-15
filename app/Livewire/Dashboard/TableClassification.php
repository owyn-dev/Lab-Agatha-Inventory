<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\DetailSale;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

final class TableClassification extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $sortField = 'classification';

    public $sortDirection = 'asc';

    public $perPage = 5;

    public $search = '';

    public $category = 'all';

    public $categoryCounts = [
        'A' => 0,
        'B' => 0,
        'C' => 0,
        'all' => 0,
    ];

    public $selectedMonth;

    public $selectedYear;

    public $results = [];

    public $grandTotal = 0;

    public $loadDataTable = false;

    public $itemsGrouped = [];

    public $totalSalesByClass = [];

    public function mount(): void
    {
        $this->selectedYear = $this->selectYear()->last();

        $this->selectedMonth = $this->selectMonth()->keys()->last();
    }

    public function render()
    {
        $this->loadResults();

        $perPage = (int) $this->perPage;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $filteredResults = array_filter(
            $this->results,
            fn ($item) => false !== mb_stripos($item['product'], $this->search) ||
            false !== mb_stripos($item['code'], $this->search)
        );

        usort(
            $filteredResults,
            fn ($a, $b) => 'asc' === $this->sortDirection
                ? $a[$this->sortField] <=> $b[$this->sortField]
                : $b[$this->sortField] <=> $a[$this->sortField]
        );

        $items = array_slice($filteredResults, ($currentPage - 1) * $perPage, $perPage);

        $paginatedData = new LengthAwarePaginator(
            $items,
            count($filteredResults),
            $this->perPage,
            $currentPage,
            ['path' => url()->current()]
        );

        return view('livewire.dashboard.table-classification', [
            'items' => $paginatedData,
        ]);
    }

    #[Computed]
    public function selectMonth()
    {
        if ( ! $this->selectedYear) {
            return collect();
        }

        return Sale::selectRaw('MONTH(transaction_date) as month_number, MONTHNAME(transaction_date) as month_name')
            ->whereYear('transaction_date', $this->selectedYear)
            ->distinct()
            ->orderBy('month_number')
            ->pluck('month_name', 'month_number');
    }

    #[Computed]
    public function selectYear()
    {
        return Sale::selectRaw('YEAR(transaction_date) as year_number')
            ->distinct()
            ->orderBy('year_number')
            ->pluck('year_number');
    }

    public function loadResults(): void
    {
        $this->loadDataTable = true;

        $startDate = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfDay();
        $endDate = (clone $startDate)->endOfMonth()->endOfDay();

        $query = DetailSale::whereHas('sale', function ($query) use ($startDate, $endDate): void {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        })
            ->with('product')
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_sold, SUM(sub_total) as total_sales')
            ->groupBy('product_id')
            ->orderByDesc('total_sales');

        $salesData = $query->get();

        $totalSales = max(1, (float) $salesData->sum('total_sales'));
        $this->grandTotal = $totalSales;

        $cumulativeSales = 0;
        $previousCumulativeSales = 0;

        $collection = $salesData->map(function ($item) use (&$cumulativeSales, &$previousCumulativeSales, $totalSales) {
            $product = $item->product;
            if ( ! $product) {
                return;
            }

            $numericSales = (float) $item->total_sales;
            $totalSold = (int) $item->total_sold;
            $unitPrice = $totalSold > 0 ? ($numericSales / $totalSold) : 0;

            $before = $previousCumulativeSales;
            $cumulativeSales += $numericSales;
            $previousCumulativeSales = $cumulativeSales;

            $percentageCumulative = ($cumulativeSales / $totalSales) * 100;

            $classification = match (true) {
                $percentageCumulative <= 80 => 'A',
                $percentageCumulative <= 95 => 'B',
                default => 'C',
            };

            $Class = match ($classification) {
                'A' => 'success',
                'B' => 'warning',
                default => 'danger',
            };

            return [
                'code' => $product->code,
                'product' => $product->name,
                'variant' => optional($product->variant)->label(),
                'total_sold' => $totalSold,
                'unit_price' => $unitPrice,
                'numeric_sales' => $numericSales,
                'total_sales' => 'Rp ' . number_format($numericSales, 0, ',', '.'),
                'formatted_sales' => 'Rp ' . number_format($numericSales, 0, ',', '.'),
                'cumulative_sales' => $cumulativeSales,
                'formatted_cumulative' => 'Rp ' . number_format($cumulativeSales, 0, ',', '.'),
                'percentage_cumulative' => number_format($percentageCumulative, 2),
                'classification' => $classification,
                'class' => $Class,
                'explanation' => [
                    'unit_price' => $unitPrice,
                    'total_sales_calc' => "{$totalSold} × Rp " . number_format($unitPrice, 0, ',', '.'),
                    'total_sales_value' => 'Rp ' . number_format($numericSales, 0, ',', '.'),
                    'percentage_calc' => '(Rp ' . number_format($cumulativeSales, 0, ',', '.') . ' / Rp ' . number_format($totalSales, 0, ',', '.') . ') × 100',
                    'percentage_value' => number_format($percentageCumulative, 2) . '%',
                    'group' => $classification,
                    'cumulative_before' => 'Rp ' . number_format($before, 0, ',', '.'),
                    'cumulative_calc' => 'Rp ' . number_format($before, 0, ',', '.') . ' + Rp ' . number_format($numericSales, 0, ',', '.'),
                    'cumulative_result' => 'Rp ' . number_format($cumulativeSales, 0, ',', '.'),
                ],
            ];
        })->filter();

        if ($this->category && 'all' !== $this->category) {
            $collection = $collection->filter(fn ($item) => $item['classification'] === $this->category);
        }

        $this->itemsGrouped = [
            'A' => $collection->where('classification', 'A')->values(),
            'B' => $collection->where('classification', 'B')->values(),
            'C' => $collection->where('classification', 'C')->values(),
        ];

        $this->totalSalesByClass = [
            'A' => $this->itemsGrouped['A']->sum('numeric_sales'),
            'B' => $this->itemsGrouped['B']->sum('numeric_sales'),
            'C' => $this->itemsGrouped['C']->sum('numeric_sales'),
        ];

        $this->results = $collection->values()->toArray();

        $this->categoryCounts = [
            'A' => $this->itemsGrouped['A']->count(),
            'B' => $this->itemsGrouped['B']->count(),
            'C' => $this->itemsGrouped['C']->count(),
            'all' => count($this->results),
        ];
    }

    #[On('yearChangedClassification')]
    public function updateYear($year): void
    {
        $this->selectedYear = $year;
        $this->loadResults();
    }

    #[On('monthChangedClassification')]
    public function updateMonth($month): void
    {
        $this->selectedMonth = $month;
        $this->loadResults();
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = 'asc' === $this->sortDirection ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
}
