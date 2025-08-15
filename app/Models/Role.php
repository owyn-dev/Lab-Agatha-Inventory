<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Model;

final class Role extends Model
{
    protected $fillable = [
        'name',
        'guard_name',
    ];

    protected function casts(): array
    {
        return [
            'name' => UserRole::class,
        ];
    }
}
