<?php

declare(strict_types=1);

namespace App\Livewire\ManageSale\Partials;

use App\Models\Sale;
use Livewire\Component;

final class DetailSaleTable extends Component
{
    public Sale $sales;

    public function mount($sales): void
    {
        $this->sales = $sales;
    }

    public function render()
    {
        $this->sales->load('detailSale.product');

        return view('livewire.manage-sale.partials.detail-sale-table');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }
}
