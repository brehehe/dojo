<?php

namespace App\Policies;

use App\Models\Pool\Pool;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PoolPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, Pool $pool): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Pool $pool): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Pool $pool): bool
    {
        return $user->hasRole('admin');
    }
}
