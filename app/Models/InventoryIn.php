<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class InventoryIn extends Model
{
    protected $table = 'inventory_in';

    protected $fillable = [
        'product_id',
        'batch_code',
        'transaction_date',
        'shelf_name',
        'stock_start',
        'current_stock',
        'unit_price',
        'expiration_date',
        'wasted',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryOut()
    {
        return $this->hasMany(InventoryOut::class, 'inventory_in_id');
    }
}
