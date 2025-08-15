<?php

declare(strict_types=1);

namespace App\Livewire\ManageProduction;

use App\Models\Production;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

final class FormProductionReport extends Component
{
    public $title;

    public $date_start;

    public $date_end;

    public $loadDataTable = false;

    public function mount($title): void
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('livewire.manage-production.form-production-report');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function generateReport(): void
    {
        $this->validate([
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ]);

        $this->loadDataTable = true;
    }

    public function exportPDF()
    {
        $this->validate([
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ]);

        $reports = Production::whereBetween('production_date', [$this->date_start, $this->date_end])
            ->with(['detailProduction.product'])
            ->get();

        $pdf = Pdf::loadView('manage-production.production-export-report', ['reports' => $reports, 'date_start' => $this->date_start, 'date_end' => $this->date_end]);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->stream();
        }, 'Production_Report_' . now()->format('Y-m-d') . '.pdf');
    }
}
