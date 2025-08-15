<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusProduction;
use Illuminate\Database\Eloquent\Model;

final class Production extends Model
{
    protected $fillable = [
        'inventory_user_id',
        'production_request_date',
        'production_user_id',
        'production_date',
        'status',
        'rejected_by',
        'note',
    ];

    protected $casts = [
        'status' => StatusProduction::class,
    ];

    public function detailProduction()
    {
        return $this->hasMany(DetailProduction::class, 'production_id');
    }

    public function inventoryUser()
    {
        return $this->belongsTo(User::class, 'inventory_user_id');
    }

    public function productionUser()
    {
        return $this->belongsTo(User::class, 'production_user_id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
