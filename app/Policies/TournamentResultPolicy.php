<?php

namespace App\Policies;

use App\Models\TournamentResult;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TournamentResultPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, TournamentResult $tournamentResult): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('panitera');
    }

    public function update(User $user, TournamentResult $tournamentResult): bool
    {
        return $user->hasRole('admin') || $user->hasRole('panitera');
    }

    public function delete(User $user, TournamentResult $tournamentResult): bool
    {
        return $user->hasRole('admin');
    }
}
