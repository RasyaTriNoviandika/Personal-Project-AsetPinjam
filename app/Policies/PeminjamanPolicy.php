<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Peminjaman;

class PeminjamanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Peminjaman $peminjaman): bool
    {
        // Admin and operator can view all, users can view their own
        return $user->hasAnyRole(['admin', 'operator']) || $peminjaman->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function update(User $user, Peminjaman $peminjaman): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function delete(User $user, Peminjaman $peminjaman): bool
    {
        return $user->isAdmin();
    }

    public function return(User $user, Peminjaman $peminjaman): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }
}