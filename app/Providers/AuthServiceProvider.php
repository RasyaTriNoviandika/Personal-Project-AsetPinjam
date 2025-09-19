<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define additional gates if needed
        Gate::define('admin-only', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('user-only', function ($user) {
            return $user->isUser();
        });

        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-barang', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-peminjaman', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-keuangan', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('view-laporan', function ($user) {
            return $user->isAdmin();
        });
    }
}