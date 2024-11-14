<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use AdminActions;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $authenticateUser)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authenticateUser, User $user)
    {
        return $authenticateUser->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authenticateUser)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authenticateUser, User $user)
    {
        return $authenticateUser->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authenticateUser, User $user)
    {
        return $authenticateUser->id === $user->id /*|| $authenticateUser->token()->client->personal_access_client*/;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $authenticateUser, User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $authenticateUser, User $user)
    {
        //
    }
}
