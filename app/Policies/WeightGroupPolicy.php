<?php

namespace App\Policies;

use App\Models\Group\WeightGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeightGroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, WeightGroup $weightGroup): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, WeightGroup $weightGroup): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, WeightGroup $weightGroup): bool
    {
        return $user->hasRole('admin');
    }
}
