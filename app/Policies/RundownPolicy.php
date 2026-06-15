<?php

namespace App\Policies;

use App\Models\Rundown\Rundown;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RundownPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, Rundown $rundown): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Rundown $rundown): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Rundown $rundown): bool
    {
        return $user->hasRole('admin');
    }
}
