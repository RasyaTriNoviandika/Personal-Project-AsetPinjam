<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ===== Role checking =====
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
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role, $roles);
    }

    // ===== Custom permission check =====
    public function hasPermission($permission)
    {
        $permissions = [
            'admin' => [
                'create', 'read', 'update', 'delete', 
                'manage_users', 'view_reports', 'export_data',
                'manage_finances', 'system_settings'
            ],
            'operator' => [
                'create', 'read', 'update', 
                'view_reports', 'export_data', 'manage_rentals'
            ],
            'user' => [
                'read', 'create_own', 'view_own', 'rent_items'
            ]
        ];

        return in_array($permission, $permissions[$this->role] ?? []);
    }

    // ===== Relationships =====
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // ===== Scopes =====
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
