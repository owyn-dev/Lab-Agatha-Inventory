<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ModelHasRole extends Model
{
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
