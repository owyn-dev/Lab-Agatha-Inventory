<?php

declare(strict_types=1);

namespace App\Livewire\ManageProduct;

use App\Models\InventoryIn;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class BarcodeScannerProduct extends Component
{
    public $batch_code;

    public $store_batch_code;

    public function render()
    {
        return view('livewire.manage-product.barcode-scanner-product');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function searchBatchCode(): void
    {
        if ( ! empty($this->batch_code)) {
            $this->store_batch_code = $this->batch_code;
        }

        $this->batch_code = '';
    }

    #[Computed]
    protected function inventoryIn()
    {
        $query = InventoryIn::with(['product'])
            ->when($this->store_batch_code, fn ($q) => $q->where('batch_code', $this->store_batch_code));

        $inventory = $query->first();

        if ( ! $inventory) {
            return;
        }

        $productId = $inventory->product_id;

        $inventory->all_production_stock = InventoryIn::where('product_id', $productId)
            ->where('wasted', 'No')
            ->sum('current_stock');

        $inventory->all_sales_stock = DB::table('product_request_batches')
            ->join('inventory_in', 'product_request_batches.inventory_in_id', '=', 'inventory_in.id')
            ->where('inventory_in.product_id', $productId)
            ->where('product_request_batches.wasted', 'No')
            ->sum('product_request_batches.current_stock');

        return $inventory;
    }
}
