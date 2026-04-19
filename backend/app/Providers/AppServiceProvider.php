<?php

namespace App\Providers;

use App\Models\Salle;
use App\Models\User;
use App\Policies\SallePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Salle::class, SallePolicy::class);

        Gate::define('admin-access', function (User $user): bool {
            return $user->role?->title === 'admin';
        });

        Gate::define('coach-access', function (User $user): bool {
            return $user->coach !== null;
        });

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
