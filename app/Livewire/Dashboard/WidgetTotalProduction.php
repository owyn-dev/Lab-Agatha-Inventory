<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\StatusProduction;
use App\Models\Production;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class WidgetTotalProduction extends Component
{
    public $selectedMonth;

    public $selectedYear;

    public function mount(): void
    {
        $this->selectedYear = $this->selectYear()->last();

        $this->selectedMonth = $this->selectMonth()->keys()->last();
    }

    public function render()
    {
        return view('livewire.dashboard.widget-total-production');
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

        return Production::selectRaw('MONTH(production_date) as month_number, MONTHNAME(production_date) as month_name')
            ->whereYear('production_date', $this->selectedYear)
            ->whereNotNull('production_user_id')
            ->whereNotIn('status', [
                StatusProduction::WAITING_FOR_RESPONSE,
                StatusProduction::PENDING_APPROVAL,
                StatusProduction::REJECTED,
            ])
            ->distinct()
            ->orderBy('month_number')
            ->pluck('month_name', 'month_number');
    }

    #[Computed]
    public function selectYear()
    {
        return Production::selectRaw('YEAR(production_date) as year_number')
            ->distinct()
            ->whereNotIn('status', [StatusProduction::WAITING_FOR_RESPONSE, StatusProduction::PENDING_APPROVAL, StatusProduction::REJECTED])
            ->orderBy('year_number')
            ->pluck('year_number');
    }

    public function updatedSelectedYear(): void
    {
        $this->selectedMonth = $this->selectMonth()->keys()->last();

        $this->dispatch('yearChangedProduction', year: $this->selectedYear);
    }

    #[Computed]
    public function production()
    {
        if ( ! $this->selectedYear) {
            return 0;
        }

        $month = $this->selectedMonth ? (int) $this->selectedMonth : null;

        $startDate = \Carbon\Carbon::createFromDate($this->selectedYear, $month ?: 1, 1)->{$month ? 'startOfMonth' : 'startOfYear'}();

        $endDate = $startDate->copy()->{$month ? 'endOfMonth' : 'endOfYear'}();

        return Production::whereBetween('production_date', [$startDate, $endDate])
            ->withCount('detailProduction')
            ->get()
            ->sum('detail_production_count');
    }
}
