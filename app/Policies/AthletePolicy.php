<?php

namespace App\Policies;

use App\Models\Athlete;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AthletePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, Athlete $athlete): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Athlete $athlete): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Athlete $athlete): bool
    {
        return $user->hasRole('admin');
    }

    public function viewOwn(User $user): bool
    {
        return $user->hasRole('contingent');
    }
}
