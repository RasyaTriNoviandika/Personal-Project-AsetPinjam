<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Barang;

class BarangPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view barang
    }

    public function view(User $user, Barang $barang): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function update(User $user, Barang $barang): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function delete(User $user, Barang $barang): bool
    {
        return $user->isAdmin();
    }
}
