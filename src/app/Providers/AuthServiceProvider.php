<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Buyer;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\BuyerPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SellerPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Buyer::class => BuyerPolicy::class,
        Seller::class => SellerPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //Passport::loadKeysFrom(__DIR__.'/storage');
        $this->registerPolicies();

        Gate::define('admin-action', function (User $user) {
            return $user->esAdministrador();
        });

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
