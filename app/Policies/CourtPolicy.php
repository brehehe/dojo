<?php

namespace App\Policies;

use App\Models\Court\Court;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourtPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, Court $court): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Court $court): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Court $court): bool
    {
        return $user->hasRole('admin');
    }
}
