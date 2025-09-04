<?php
namespace App\Models;

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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->role) {
                $model->role = 'user';
            }
            if (!$model->status) {
                $model->status = 'active';
            }
        });
    }

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isOperator()
    {
        return $this->role === 'operator';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }

    public function can($permission, $model = null)
    {
        // Basic permission system based on role
        $permissions = [
            'admin' => ['*'], // Admin can do everything
            'operator' => [
                'view_dashboard', 'manage_barang', 'manage_peminjam', 
                'manage_peminjaman', 'view_reports'
            ],
            'user' => [
                'view_dashboard', 'view_own_peminjaman', 'create_peminjaman'
            ]
        ];

        $userPermissions = $permissions[$this->role] ?? [];
        
        if (in_array('*', $userPermissions)) {
            return true;
        }

        return in_array($permission, $userPermissions);
    }

    // Relationships
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Scopes
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeUser($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeOperator($query)
    {
        return $query->where('role', 'operator');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Accessors
    public function getRoleNameAttribute()
    {
        $roles = [
            'admin' => 'Administrator',
            'user' => 'Pengguna',
            'operator' => 'Operator'
        ];

        return $roles[$this->role] ?? 'Unknown';
    }

    public function getRoleBadgeAttribute()
    {
        $badges = [
            'admin' => 'bg-danger',
            'operator' => 'bg-warning',
            'user' => 'bg-primary'
        ];

        return $badges[$this->role] ?? 'bg-secondary';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active' ? 'bg-success' : 'bg-secondary';
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getTotalPeminjamanAttribute()
    {
        return $this->peminjaman()->count();
    }

    public function getPeminjamanAktifAttribute()
    {
        return $this->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->count();
    }

    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return $initials;
    }

    // Methods
    public function canAccessFinancial()
    {
        return $this->isAdmin();
    }

    public function canManageUsers()
    {
        return $this->isAdmin();
    }

    public function canManageData()
    {
        return $this->hasAnyRole(['admin', 'operator']);
    }

    public function canViewReports()
    {
        return $this->hasAnyRole(['admin', 'operator']);
    }

    public function getLastLogin()
    {
        // You might want to track last login in a separate column
        return $this->updated_at;
    }

    public function hasActivePeminjaman()
    {
        return $this->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->exists();
    }
}