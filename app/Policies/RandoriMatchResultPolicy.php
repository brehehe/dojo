<?php

namespace App\Policies;

use App\Models\RandoriMatchResult;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RandoriMatchResultPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, RandoriMatchResult $randoriMatchResult): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('panitera');
    }

    public function update(User $user, RandoriMatchResult $randoriMatchResult): bool
    {
        return $user->hasRole('admin') || $user->hasRole('panitera');
    }

    public function delete(User $user, RandoriMatchResult $randoriMatchResult): bool
    {
        return $user->hasRole('admin');
    }
}
