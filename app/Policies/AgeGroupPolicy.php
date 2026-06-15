<?php

namespace App\Policies;

use App\Models\Group\AgeGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgeGroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, AgeGroup $ageGroup): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, AgeGroup $ageGroup): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, AgeGroup $ageGroup): bool
    {
        return $user->hasRole('admin');
    }
}
