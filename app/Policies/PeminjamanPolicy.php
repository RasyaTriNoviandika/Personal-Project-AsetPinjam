<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeminjamanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Peminjaman $peminjaman)
    {
        // Admin dan operator bisa lihat semua
        if (in_array($user->role, ['admin', 'operator'])) {
            return true;
        }

        // User hanya bisa lihat peminjaman sendiri
        return $user->id === $peminjaman->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Semua role bisa buat peminjaman
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Peminjaman $peminjaman)
    {
        // Hanya admin dan operator yang bisa update
        return in_array($user->role, ['admin', 'operator']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Peminjaman $peminjaman)
    {
        // Hanya admin yang bisa delete
        return $user->role === 'admin';
    }
}