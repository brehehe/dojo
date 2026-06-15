<?php

namespace App\Policies;

use App\Models\DrawingMatchNumber;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DrawingMatchNumberPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function view(User $user, DrawingMatchNumber $drawingMatchNumber): bool
    {
        return $user->hasRole('admin') || $user->hasRole('koordinator') || $user->hasRole('panitera');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, DrawingMatchNumber $drawingMatchNumber): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, DrawingMatchNumber $drawingMatchNumber): bool
    {
        return $user->hasRole('admin');
    }
}
