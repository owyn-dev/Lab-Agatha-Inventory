<?php

declare(strict_types=1);

namespace App\Livewire\ManageSale\Partials;

use App\Models\ProductRequest;
use Livewire\Component;

final class DetailProductRequestTable extends Component
{
    public ProductRequest $product_request;

    public function mount($product_request): void
    {
        $this->product_request = $product_request;
    }

    public function render()
    {
        $this->product_request->load('detailProductRequest.product', 'detailProductRequest.productRequestBatches.inventoryIn');

        return view('livewire.manage-sale.partials.detail-product-request-table');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }
}
