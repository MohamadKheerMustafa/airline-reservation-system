<?php

namespace App\Policies;

use App\Models\Airline;
use App\Models\Plane;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AirlinePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user;
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, $id)
    {
        return $user;
    }

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
    public function update(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->is_admin;
    }
}
