<?php

declare(strict_types=1);

namespace App\Livewire\ManageInventory;

use App\Models\InventoryIn;
use App\Models\InventoryOut;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

final class FormInventoryReport extends Component
{
    public $title;

    public $date_start;

    public $date_end;

    public $currentTab = 'InventoryIn';

    public $loadDataTable = false;

    public function mount($title): void
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('livewire.manage-inventory.form-inventory-report');
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

    public function exportInventoryInPdf()
    {
        if ( ! $this->date_start || ! $this->date_end) {
            session()->flash('error', 'Please select a date range first.');

            return;
        }

        $inventoryIn = InventoryIn::whereBetween('transaction_date', [$this->date_start, $this->date_end])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $inventoryIn->load('product');

        $data = [
            'title' => 'Inventory In Report',
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'inventoryIn' => $inventoryIn,
        ];

        $pdf = Pdf::loadView('manage-inventory.inventory-in-export-report', $data)->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, "inventory_in_{$this->date_start}_to_{$this->date_end}.pdf");
    }

    public function exportInventoryOutPdf()
    {
        if ( ! $this->date_start || ! $this->date_end) {
            session()->flash('error', 'Please select a date range first.');

            return;
        }

        $inventoryOut = InventoryOut::whereBetween('transaction_date', [$this->date_start, $this->date_end])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $inventoryOut->load('inventoryIn.product');

        $data = [
            'title' => 'Inventory Out Report',
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'inventoryOut' => $inventoryOut,
        ];

        $pdf = Pdf::loadView('manage-inventory.inventory-out-export-report', $data)->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, "inventory_out_{$this->date_start}_to_{$this->date_end}.pdf");
    }
}
