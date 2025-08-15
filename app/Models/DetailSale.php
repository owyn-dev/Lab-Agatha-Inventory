<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class DetailSale extends Model
{
    protected $fillable = [
        'sales_id',
        'product_id',
        'quantity',
        'price',
        'sub_total',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sales_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
