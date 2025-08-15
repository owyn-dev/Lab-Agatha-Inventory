<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

final class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'full_name',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function modelHasRoles()
    {
        return $this->morphOne(ModelHasRole::class, 'model');
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
