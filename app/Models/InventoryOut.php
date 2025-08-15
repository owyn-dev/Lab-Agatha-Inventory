<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class InventoryOut extends Model
{
    protected $table = 'inventory_out';

    protected $fillable = [
        'inventory_in_id',
        'batch_code',
        'transaction_date',
        'shelf_name',
        'stock_out',
    ];

    public function inventoryIn()
    {
        return $this->belongsTo(InventoryIn::class);
    }
}
