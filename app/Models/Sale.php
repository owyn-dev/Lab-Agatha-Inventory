<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Sale extends Model
{
    protected $fillable = [
        'sales_user_id',
        'transaction_date',
        'total_amount',
    ];

    public function detailSale()
    {
        return $this->hasMany(DetailSale::class, 'sales_id');
    }

    public function salesUser()
    {
        return $this->belongsTo(User::class);
    }
}
