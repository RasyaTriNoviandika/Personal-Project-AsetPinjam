<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use App\Models\User;
use App\Models\Peminjaman;
use App\Policies\UserPolicy;
use App\Policies\PeminjamanPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Peminjaman::class => PeminjamanPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for specific permissions
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-finances', function (User $user) {
            return $user->hasAnyRole(['admin', 'operator']);
        });

        Gate::define('view-reports', function (User $user) {
            return $user->hasAnyRole(['admin', 'operator']);
        });

        Gate::define('export-data', function (User $user) {
            return $user->hasAnyRole(['admin', 'operator']);
        });

        Gate::define('manage-system-settings', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('delete-records', function (User $user) {
            return $user->isAdmin();
        });
    }
}