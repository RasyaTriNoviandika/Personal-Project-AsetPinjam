<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ================= Role Helper Methods =================

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOperator()
    {
        return $this->role === 'operator';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }
        return in_array($this->role, $roles);
    }

    public function canAccess($permission)
    {
        return \App\Helpers\RoleHelper::canAccess($this->role, $permission);
    }

    public function getRoleColorAttribute()
    {
        return \App\Helpers\RoleHelper::getRoleColor($this->role);
    }

    public function getRoleNameAttribute()
    {
        return \App\Helpers\RoleHelper::getRoleName($this->role);
    }

    public function getMenuItems()
    {
        return \App\Helpers\RoleHelper::getMenuByRole($this->role);
    }

    public function canViewFinancialData()
    {
        return \App\Helpers\RoleHelper::canViewFinancialData($this->role);
    }

    public function canManageUsers()
    {
        return \App\Helpers\RoleHelper::canManageUsers($this->role);
    }

    public function canDeleteData()
    {
        return \App\Helpers\RoleHelper::canDeleteData($this->role);
    }

    public function canExportData($type = 'operational')
    {
        return \App\Helpers\RoleHelper::canExportData($this->role, $type);
    }

    // ================= Relationships =================

    public function peminjaman()
    {
        return $this->hasMany(\App\Models\Peminjaman::class);
    }

    public function createdPeminjam()
    {
        return $this->hasMany(\App\Models\Peminjam::class, 'created_by');
    }

    // ================= Scopes =================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeOperators($query)
    {
        return $query->where('role', 'operator');
    }

    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }

    // ================= Status Methods =================

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function activate()
    {
        return $this->update(['status' => 'active']);
    }

    public function deactivate()
    {
        return $this->update(['status' => 'inactive']);
    }

    // ================= Statistics Methods =================

    public function getTotalPeminjamanAttribute()
    {
        return $this->peminjaman()->count();
    }

    public function getActivePeminjamanAttribute()
    {
        return $this->peminjaman()
                    ->whereIn('status', ['dipinjam', 'terlambat'])
                    ->count();
    }

    public function getCompletedPeminjamanAttribute()
    {
        return $this->peminjaman()
                    ->where('status', 'dikembalikan')
                    ->count();
    }

    public function getOverduePeminjamanAttribute()
    {
        return $this->peminjaman()
                    ->where('status', 'terlambat')
                    ->count();
    }
}