<?php

namespace Parking\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Parking\User;

/** @codeCoverageIgnore */
class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Parking\Model' => 'Parking\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();

        Gate::define('update-lot', function (User $user) {
            return $user->role === User::ROLE_ADMIN;
        });
    }
}
