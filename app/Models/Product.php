<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VariantProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'image',
        'variant',
        'price',
        'expired_day',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'variant' => VariantProduct::class,
    ];

    public function inventoryIn()
    {
        return $this->hasMany(InventoryIn::class, 'product_id');
    }

    public function detailSale()
    {
        return $this->hasMany(DetailSale::class, 'product_id');
    }

    public function detailProduction()
    {
        return $this->hasMany(DetailProduction::class, 'product_id');
    }
}
