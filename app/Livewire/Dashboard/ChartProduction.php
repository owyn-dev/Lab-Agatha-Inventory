<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\StatusProduction;
use App\Models\Production;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

final class ChartProduction extends Component
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
        return view('livewire.dashboard.chart-production');
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
        return Production::selectRaw('YEAR(production_date) as year_number')
            ->distinct()
            ->where('production_user_id', '!=', null)
            ->whereNotIn('status', [StatusProduction::WAITING_FOR_RESPONSE, StatusProduction::PENDING_APPROVAL, StatusProduction::REJECTED])
            ->orderBy('year_number')
            ->pluck('year_number');
    }

    #[On('yearChangedProduction')]
    public function updateYear($year): void
    {
        $this->selectedYear = $year;
        $this->chartData();
    }

    public function chartData(): void
    {
        $this->chartData = Production::selectRaw('MONTH(production_date) as month')
            ->whereYear('production_date', $this->selectedYear)
            ->whereNotIn('status', [StatusProduction::WAITING_FOR_RESPONSE, StatusProduction::PENDING_APPROVAL, StatusProduction::REJECTED])
            ->withCount('detailProduction')
            ->get()
            ->groupBy('month')
            ->mapWithKeys(fn ($group, $month) => [$month => $group->sum('detail_production_count')])
            ->sortKeys()
            ->toArray();

        $defaultMonths = array_fill(1, 12, 0);
        $this->chartData = array_values(array_replace($defaultMonths, $this->chartData));

        $this->dispatch('updateTheChart');
    }
}
