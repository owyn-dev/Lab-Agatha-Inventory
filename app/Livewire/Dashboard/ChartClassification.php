<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\DetailSale;
use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class ChartClassification extends Component
{
    public $selectedMonth = null;

    public $selectedYear = null;

    public $chartData;

    public function mount(): void
    {
        $this->selectedYear = $this->selectYear()->last();
        $this->selectedMonth = $this->selectMonth()->keys()->last();
        $this->loadChartData();
    }

    public function render()
    {
        return view('livewire.dashboard.chart-classification');
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

    public function updatedSelectedYear(): void
    {
        $this->selectedMonth = (string) $this->selectMonth()->keys()->last();
        $this->loadChartData();

        $this->dispatch('yearChangedClassification', year: $this->selectedYear);
    }

    public function updatedSelectedMonth(): void
    {
        $this->loadChartData();

        $this->dispatch('monthChangedClassification', month: $this->selectedMonth);
    }

    public function loadChartData(): void
    {
        $query = DetailSale::whereHas('sale', function ($query): void {
            $query->whereYear('transaction_date', $this->selectedYear);

            if ( ! empty($this->selectedMonth)) {
                $query->whereMonth('transaction_date', $this->selectedMonth);
            }
        })
            ->with('product')
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_sold, SUM(sub_total) as total_sales')
            ->groupBy('product_id')
            ->orderByDesc('total_sales');

        $salesData = $query->get();

        $totalSales = max(1, (float) $salesData->sum('total_sales'));
        $totalProducts = max(1, $salesData->count());

        $cumulativeSales = 0;
        $previousCumulativeSales = 0;

        $collection = $salesData->map(function ($item) use (&$cumulativeSales, &$previousCumulativeSales, $totalSales) {
            $product = $item->product;
            if ( ! $product) {
                return;
            }

            $numericSales = (float) $item->total_sales;

            $before = $previousCumulativeSales;
            $cumulativeSales += $numericSales;
            $previousCumulativeSales = $cumulativeSales;

            $percentageCumulative = ($cumulativeSales / $totalSales) * 100;

            $classification = match (true) {
                $percentageCumulative <= 80 => 'A',
                $percentageCumulative <= 95 => 'B',
                default => 'C',
            };

            return [
                'classification' => $classification,
            ];
        })->filter();

        $productCounts = [
            'A' => $collection->where('classification', 'A')->count() ?: 0,
            'B' => $collection->where('classification', 'B')->count() ?: 0,
            'C' => $collection->where('classification', 'C')->count() ?: 0,
        ];

        $productPercentages = [
            'A' => $productCounts['A'] > 0 ? round(($productCounts['A'] / $totalProducts) * 100, 2) : 0,
            'B' => $productCounts['B'] > 0 ? round(($productCounts['B'] / $totalProducts) * 100, 2) : 0,
            'C' => $productCounts['C'] > 0 ? round(($productCounts['C'] / $totalProducts) * 100, 2) : 0,
        ];

        $this->chartData = [
            'A' => [
                'product_count' => $productCounts['A'],
                'product_percentage' => $productPercentages['A'],
            ],
            'B' => [
                'product_count' => $productCounts['B'],
                'product_percentage' => $productPercentages['B'],
            ],
            'C' => [
                'product_count' => $productCounts['C'],
                'product_percentage' => $productPercentages['C'],
            ],
        ];

        $this->dispatch('updateTheChart');
    }
}
