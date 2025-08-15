<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\InventoryIn;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class TableInventoryIn extends Component
{
    public function render()
    {
        return view('livewire.dashboard.table-inventory-in');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    #[Computed]
    public function inventoryIn()
    {
        return InventoryIn::select('id', 'product_id', 'batch_code', 'shelf_name', 'current_stock', 'expiration_date')
            ->with(['product:id,name,expired_day'])
            ->where('current_stock', '>', 0)
            ->whereBetween('expiration_date', [\Carbon\Carbon::now(), \Carbon\Carbon::now()->addDays(30)])
            ->orderBy('expiration_date', 'asc')
            ->limit(5)
            ->get();
    }
}
