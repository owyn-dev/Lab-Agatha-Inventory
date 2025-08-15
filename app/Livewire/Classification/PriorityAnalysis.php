<?php

declare(strict_types=1);

namespace App\Livewire\Classification;

use App\Models\DetailSale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

final class PriorityAnalysis extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $title;

    public $date_start;

    public $date_end;

    public $sortField = 'total_percentage';

    public $sortDirection = 'asc';

    public $perPage = 10;

    public $search = '';

    public $results = [];

    public $grandTotal = 0;

    public $grandTotalPerPage = 0;

    public $method = 'methodClassificationABC2';

    public $loadDataTable = false;

    public $itemsGrouped = [];

    public $totalSalesByClass = [];

    public function mount($title): void
    {
        $this->title = $title;
    }

    public function render()
    {
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

        $this->grandTotalPerPage = collect($items)->sum(function ($item) {
            $value = $item['numeric_sales'] ?? (
                is_numeric($item['total_sales']) ? $item['total_sales'] : (float) preg_replace('/[^\d]/', '', $item['total_sales'])
            );

            return (float) $value;
        });

        $paginatedData = new LengthAwarePaginator(
            $items,
            count($filteredResults),
            $this->perPage,
            $currentPage,
            ['path' => url()->current()]
        );

        return view('livewire.classification.priority-analysis', [
            'items' => $paginatedData,
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
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

    public function loadResults(): void
    {
        $this->validate([
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ]);

        $this->date_start = Carbon::parse($this->date_start)->startOfDay();
        $this->date_end = Carbon::parse($this->date_end)->endOfDay();

        $this->methodClassificationABC2();
        $this->loadDataTable = true;
    }

    public function methodClassificationABC2(): void
    {
        $this->method = 'methodClassificationABC2';
        $this->sortField = 'classification';

        $query = DetailSale::whereHas('sale', function ($query): void {
            $query->whereBetween('transaction_date', [$this->date_start, $this->date_end]);
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
                'total_sales' => 'Rp ' . number_format((float) $item->total_sales, 0, ',', '.'),
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
    }

    public function exportPDF()
    {
        $this->validate([
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ]);

        if (empty($this->results)) {
            $this->methodClassificationABC2();
        }

        $results = $this->results;
        $grandTotal = $this->grandTotal;
        $date_start = $this->date_start;
        $date_end = $this->date_end;
        $title = $this->title;

        $pdf = Pdf::loadView('classification.classification-export-report', compact(
            'results',
            'grandTotal',
            'date_start',
            'date_end',
            'title'
        ))->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->stream();
        }, 'Priority_Analysis_Report_' . now()->format('Y-m-d') . '.pdf');
    }
}
