<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Permission levels:
         * 1 is normal user
         * 2-8 are unused
         * 9 is admin
         */
        Gate::define('admin-only', function ($user) {
            if ($user->permission >= 9) {
                return true;
            } else {
                return false;
            }
        });
    }
}
