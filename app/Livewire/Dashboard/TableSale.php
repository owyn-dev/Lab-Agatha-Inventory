<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Sale;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class TableSale extends Component
{
    public function render()
    {
        return view('livewire.dashboard.table-sale');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    #[Computed]
    public function sale()
    {
        return Sale::select('id', 'sales_user_id', 'transaction_date', 'total_amount')
            ->with('salesUser')
            ->withCount('detailSale')
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();
    }
}
