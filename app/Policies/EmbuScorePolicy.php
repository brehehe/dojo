<?php

namespace App\Policies;

use App\Models\EmbuScore;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmbuScorePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, EmbuScore $embuScore): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('panitera');
    }

    public function update(User $user, EmbuScore $embuScore): bool
    {
        return $user->hasRole('admin') || $user->hasRole('panitera');
    }

    public function delete(User $user, EmbuScore $embuScore): bool
    {
        return $user->hasRole('admin');
    }
}
