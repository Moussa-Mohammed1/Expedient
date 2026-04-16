<?php

namespace App\Policies;

use App\Models\Salle;
use App\Models\User;

class SallePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Salle $salle): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->coach !== null;
    }

    public function update(User $user, Salle $salle): bool
    {
        return (int) ($user->coach?->id ?? 0) === (int) $salle->coach_id;
    }

    public function delete(User $user, Salle $salle): bool
    {
        return (int) ($user->coach?->id ?? 0) === (int) $salle->coach_id;
    }

    public function restore(User $user, Salle $salle): bool
    {
        return false;
    }

    public function forceDelete(User $user, Salle $salle): bool
    {
        return false;
    }
}