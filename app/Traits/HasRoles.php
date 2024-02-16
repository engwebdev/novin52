<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Role;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        // config('roles.models.role')
        return $this->belongsToMany(
            Role::class,
            'role_user',
            'user_id',
            'role_id',
            'id',
            'id'
        )
            ->withTimestamps();
    }

    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return $this->roles->whereIn('name', $role)->isNotEmpty();
        }

        return $this->roles->where('name', $role)->isNotEmpty();
    }
}
