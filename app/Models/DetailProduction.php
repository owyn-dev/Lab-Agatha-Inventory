<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class DetailProduction extends Model
{
    protected $fillable = [
        'production_id',
        'product_id',
        'batch_code',
        'shelf_name',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }
}
