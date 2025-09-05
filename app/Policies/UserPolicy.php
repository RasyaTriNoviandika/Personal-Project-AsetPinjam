<?php
// app/Policies/UserPolicy.php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model)
    {
        // Admin can view any user, users can view their own profile
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model)
    {
        // Admin can update any user, users can update their own profile
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        // Only admin can delete, but not themselves
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can manage roles.
     */
    public function manageRoles(User $user)
    {
        return $user->isAdmin();
    }
}