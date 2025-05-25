<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('master', function ($user) {
            return $user->hasRole('master');
        });
        Gate::define('admin', function ($user) {
            return $user->hasRole('admin');
        });
        Gate::define('user', function ($user) {
            return $user->hasRole('user');
        });
    }
}
