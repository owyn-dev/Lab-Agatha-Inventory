<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

final class ChartSale extends Component
{
    public $selectedYear;

    public $chartData;

    public function mount(): void
    {
        $this->selectedYear = $this->selectYear()->last();

        $this->chartData();
    }

    public function render()
    {
        return view('livewire.dashboard.chart-sale');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function updatedSelectedYear(): void
    {
        $this->chartData();
    }

    #[Computed]
    public function selectYear()
    {
        return Sale::selectRaw('YEAR(transaction_date) as year_number')
            ->distinct()
            ->orderBy('year_number')
            ->pluck('year_number');
    }

    #[On('yearChangedTransaction')]
    public function updateYear($year): void
    {
        $this->selectedYear = $year;
        $this->chartData();
    }

    public function chartData(): void
    {
        $this->chartData = Sale::selectRaw('MONTH(transaction_date) as month, SUM(total_amount) as total')
            ->whereYear('transaction_date', $this->selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $defaultMonths = array_fill(1, 12, 0);
        $this->chartData = array_values(array_replace($defaultMonths, $this->chartData));

        $this->dispatch('updateTheChart');
    }
}
