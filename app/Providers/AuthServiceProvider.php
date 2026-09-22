<?php

namespace App\Providers;

use Diadal\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        \Laravel\Passport\Passport::useClientModel(\Diadal\Passport\Client::class);
        \Laravel\Passport\Passport::usePersonalAccessClientModel(\Diadal\Passport\PersonalAccessClient::class);

        Passport::tokensCan([
            'all' => 'All Function',
            'create-invoice' => 'Create Invoice',
        ]);

        Passport::tokensExpireIn(now()->addDays(15));

        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
