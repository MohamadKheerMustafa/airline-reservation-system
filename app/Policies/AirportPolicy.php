<?php

namespace App\Policies;

use App\Models\Airport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AirportPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Airport $airport): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Airport $airport): bool
    {
        return $user->is_admin;
    }
}
