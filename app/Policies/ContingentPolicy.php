<?php

namespace App\Policies;

use App\Models\Contingent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContingentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, Contingent $contingent): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Contingent $contingent): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Contingent $contingent): bool
    {
        return $user->hasRole('admin');
    }

    public function viewOwn(User $user): bool
    {
        return $user->hasRole('contingent');
    }
}
