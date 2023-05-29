<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\User\UserRoleEnum;
use App\Traits\HasUuid;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

final class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasUuid, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRoleEnum::class,
    ];

    /* Relationships */

//    public function animals(): HasMany
//    {
//        return $this->hasMany(Animal::class);
//    }

    /* Auth */

    public function canAccessFilament(): bool
    {
        return $this->hasRole(UserRoleEnum::Admin);
    }

    /* Helpers */

    public function hasRole(array|string|UserRoleEnum $role): bool
    {
        if (is_string($role)) {
            $role = UserRoleEnum::from($role);
        }

        if (! is_array($role)) {
            $role = [$role];
        }

        $role = array_map(static fn (mixed $role) => UserRoleEnum::from($role), $role);

        return in_array($this->role, $role, true);
    }
}
