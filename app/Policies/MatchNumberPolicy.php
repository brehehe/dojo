<?php

namespace App\Policies;

use App\Models\MatchNumber\MatchNumber;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MatchNumberPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, MatchNumber $matchNumber): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, MatchNumber $matchNumber): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, MatchNumber $matchNumber): bool
    {
        return $user->hasRole('admin');
    }
}
