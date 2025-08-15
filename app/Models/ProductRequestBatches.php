<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ProductRequestBatches extends Model
{
    protected $fillable = [
        'product_request_item_id',
        'inventory_in_id',
        'allocated_quantity',
        'current_stock',
    ];

    public function productRequestItem()
    {
        return $this->belongsTo(DetailProductRequest::class, 'product_request_item_id');
    }

    public function inventoryIn()
    {
        return $this->belongsTo(InventoryIn::class, 'inventory_in_id');
    }
}
