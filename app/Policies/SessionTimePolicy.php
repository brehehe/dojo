<?php

namespace App\Policies;

use App\Models\SessionTime;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SessionTimePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, SessionTime $sessionTime): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, SessionTime $sessionTime): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, SessionTime $sessionTime): bool
    {
        return $user->hasRole('admin');
    }
}
