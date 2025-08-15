<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\InventoryOut;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class TableInventoryOut extends Component
{
    public function render()
    {
        return view('livewire.dashboard.table-inventory-out');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    #[Computed]
    public function inventoryOut()
    {
        return InventoryOut::select('id', 'inventory_in_id', 'batch_code', 'transaction_date', 'shelf_name', 'stock_out')
            ->with('inventoryIn.product')
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();
    }
}
