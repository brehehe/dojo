<?php

namespace App\Policies;

use App\Models\Referee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefereePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, Referee $referee): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Referee $referee): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Referee $referee): bool
    {
        return $user->hasRole('admin');
    }
}
