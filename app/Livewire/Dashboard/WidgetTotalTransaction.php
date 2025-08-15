<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class WidgetTotalTransaction extends Component
{
    public $selectedMonth = null;

    public $selectedYear = null;

    public function mount(): void
    {
        $this->selectedYear = $this->selectYear()->last();

        $this->selectedMonth = $this->selectMonth()->keys()->last();
    }

    public function render()
    {
        return view('livewire.dashboard.widget-total-transaction');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
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

        $this->dispatch('yearChangedTransaction', year: $this->selectedYear);
    }

    #[Computed]
    public function sales()
    {
        if ( ! $this->selectedYear) {
            return 0;
        }

        $month = $this->selectedMonth ? (int) $this->selectedMonth : null;

        $startDate = \Carbon\Carbon::createFromDate($this->selectedYear, $month ?: 1, 1)->{$month ? 'startOfMonth' : 'startOfYear'}();

        $endDate = $startDate->copy()->{$month ? 'endOfMonth' : 'endOfYear'}();

        return Sale::whereBetween('transaction_date', [$startDate, $endDate])->sum('total_amount');
    }
}
