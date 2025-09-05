<?php
// app/Policies/PeminjamanPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeminjamanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view listings
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Peminjaman $peminjaman)
    {
        // Admin and operator can view all, users can only view their own
        return $user->hasAnyRole(['admin', 'operator']) || $peminjaman->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Peminjaman $peminjaman)
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Peminjaman $peminjaman)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can process returns.
     */
    public function processReturn(User $user, Peminjaman $peminjaman)
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}