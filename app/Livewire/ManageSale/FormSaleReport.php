<?php

declare(strict_types=1);

namespace App\Livewire\ManageSale;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

final class FormSaleReport extends Component
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
        return view('livewire.manage-sale.form-sale-report');
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

        $reports = Sale::whereBetween('transaction_date', [$this->date_start, $this->date_end])
            ->with(['detailSale.product'])
            ->get();

        $totalAmount = $reports->sum('total_amount');
        $pdf = Pdf::loadView('manage-sales.sales-export-report', ['reports' => $reports, 'totalAmount' => $totalAmount, 'date_start' => $this->date_start, 'date_end' => $this->date_end]);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->stream();
        }, 'Sales_Report_' . now()->format('Y-m-d') . '.pdf');
    }
}
