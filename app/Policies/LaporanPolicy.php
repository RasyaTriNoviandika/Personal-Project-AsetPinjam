<?php

namespace App\Policies;

use App\Models\User;

class LaporanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'operator']);
    }

    public function viewKeuangan(User $user): bool
    {
        return $user->isAdmin(); // Only admin can view financial reports
    }
}