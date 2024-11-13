<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //Passport::loadKeysFrom(__DIR__.'/storage');
        $this->registerPolicies();

        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(1));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        //habilitamos explicitamente password grant del middlewre auth:api
        Passport::enablePasswordGrant();

        //Scopes
        Passport::tokensCan([
            'purchase-product' => 'Create transactions to buy products',
            'manage-product' => 'Create, get, update and delete products',
            'manage-account' => 'Get info about account, name, mail, status, update email data, name and password. Can not delete the account',
            'read-general' => 'Get general information'
        ]);
    }
}
