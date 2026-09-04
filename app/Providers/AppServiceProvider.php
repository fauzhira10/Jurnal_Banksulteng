<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Gate umum untuk fitur yang hanya boleh diakses Admin Pusat
        Gate::define('admin-only', fn (User $user) => $user->isAdmin());
    }
}
