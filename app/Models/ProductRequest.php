<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusProductRequest;
use Illuminate\Database\Eloquent\Model;

final class ProductRequest extends Model
{
    protected $fillable = [
        'sales_user_id',
        'product_request_date',
        'handled_by',
        'handled_date',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => StatusProductRequest::class,
    ];

    public function salesUser()
    {
        return $this->belongsTo(User::class, 'sales_user_id');
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function detailProductRequest()
    {
        return $this->hasMany(DetailProductRequest::class, 'product_request_id');
    }
}
