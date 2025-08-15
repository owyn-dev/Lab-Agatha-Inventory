<?php

declare(strict_types=1);

namespace App\Livewire\ManageProduction\Partials;

use App\Models\DetailProduction;
use App\Models\Production;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Milon\Barcode\DNS1D;

final class DetailProductionTable extends Component
{
    public Production $production;

    public function mount($production): void
    {
        $this->production = $production;
    }

    public function render()
    {
        $this->production->load('detailProduction.product');

        return view('livewire.manage-production.partials.detail-production-table');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function generateBarcodePdf($detail_production_id)
    {
        $detail_production = DetailProduction::findOrFail($detail_production_id);

        $detail_production->load('product');

        $barcodes = [];
        $barcodeGenerator = new DNS1D();

        for ($i = 0; $i < $detail_production->quantity; $i++) {
            $barcodes[] = [
                'product_name' => $detail_production->product->name,
                'code' => $detail_production->product->code,
                'barcode' => $barcodeGenerator->getBarcodeHTML($detail_production->batch_code, 'C128', 2, 40),
                'expiry_date' => \Carbon\Carbon::parse($detail_production->production->production_date)
                    ->addDays($detail_production->product->expired_day)
                    ->format('Y-m-d'),
            ];
        }

        $pdf = Pdf::loadView('components.pdf-barcode', compact('barcodes'));

        $filename = 'Production_Barcodes_' . now()->format('Ymd') . '.pdf';

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, $filename);
    }
}
