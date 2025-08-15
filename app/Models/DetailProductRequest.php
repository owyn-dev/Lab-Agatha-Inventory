<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class DetailProductRequest extends Model
{
    protected $fillable = [
        'product_request_id',
        'product_id',
        'requested_quantity',
    ];

    public function productRequest()
    {
        return $this->belongsTo(ProductRequest::class, 'product_request_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productRequestBatches()
    {
        return $this->hasMany(ProductRequestBatches::class, 'product_request_item_id');
    }
}
