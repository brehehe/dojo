<?php

namespace App\Policies;

use App\Models\ActiveCourtReferee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActiveCourtRefereePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, ActiveCourtReferee $activeCourtReferee): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('panitera');
    }

    public function update(User $user, ActiveCourtReferee $activeCourtReferee): bool
    {
        return $user->hasRole('admin') || $user->hasRole('panitera');
    }

    public function delete(User $user, ActiveCourtReferee $activeCourtReferee): bool
    {
        return $user->hasRole('admin');
    }
}
