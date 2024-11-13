<?php

use App\Http\Controllers\Product\ProductBuyerTransactionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::apiResource('users', 'App\Http\Controllers\User\UserController');
Route::apiResource('categories', 'App\Http\Controllers\Category\CategoryController');
Route::apiResource('products', 'App\Http\Controllers\Product\ProductController')->only(['index', 'show']);
Route::apiResource('transactions', 'App\Http\Controllers\Transaction\TransactionController')->only(['index', 'show']);
Route::apiResource('buyers', 'App\Http\Controllers\Buyer\BuyerController')->only(['index', 'show']);
Route::apiResource('sellers', 'App\Http\Controllers\Seller\SellerController')->only(['index', 'show']);

// Nested resources
Route::group(['prefix' => 'categories/{category}'], function () {
    Route::apiResource('products', 'App\Http\Controllers\Category\CategoryProductController')->only(['index'])->names([
        'index' => 'categories.products.index'
    ]);
    Route::apiResource('sellers', 'App\Http\Controllers\Category\CategorySellerController')->only(['index'])->names([
        'index' => 'categories.sellers.index'
    ]);
    Route::apiResource('transactions', 'App\Http\Controllers\Category\CategoryTransactionController')->only(['index'])->names([
        'index' => 'categories.transactions.index'
    ]);
    Route::apiResource('buyers', 'App\Http\Controllers\Category\CategoryBuyerController')->only(['index'])->names([
        'index' => 'categories.buyers.index'
    ]);
});

Route::group(['prefix' => 'transactions/{transaction}'], function () {
    Route::apiResource('categories', 'App\Http\Controllers\Transaction\TransactionCategoryController')->only(['index'])->names([
        'index' => 'transactions.categories.index'
    ]);
    Route::apiResource('sellers', 'App\Http\Controllers\Transaction\TransactionSellerController')->only(['index'])->names([
        'index' => 'transactions.sellers.index'
    ]);
});

Route::group(['prefix' => 'buyers/{buyer}'], function () {
    Route::apiResource('transactions', 'App\Http\Controllers\Buyer\BuyerTransactionController')->only(['index'])->names([
        'index' => 'buyers.transactions.index'
    ]);
    Route::apiResource('products', 'App\Http\Controllers\Buyer\BuyerProductsController')->only(['index'])->names([
        'index' => 'buyers.products.index'
    ]);
    Route::apiResource('sellers', 'App\Http\Controllers\Buyer\BuyerSellerController')->only(['index'])->names([
        'index' => 'buyers.sellers.index'
    ]);
    Route::apiResource('categories', 'App\Http\Controllers\Buyer\BuyerCategoryController')->only(['index'])->names([
        'index' => 'buyers.categories.index'
    ]);
});

Route::group(['prefix' => 'sellers/{seller}'], function () {
    Route::apiResource('transactions', 'App\Http\Controllers\Seller\SellerTransactionController')->only(['index'])->names([
        'index' => 'sellers.transactions.index'
    ]);
    Route::apiResource('categories', 'App\Http\Controllers\Seller\SellerCategoryController')->only(['index'])->names([
        'index' => 'sellers.categories.index'
    ]);
    Route::apiResource('buyers', 'App\Http\Controllers\Seller\SellerBuyerController')->only(['index'])->names([
        'index' => 'sellers.buyers.index'
    ]);
    Route::apiResource('products', 'App\Http\Controllers\Seller\SellerProductController')->except(['edit', 'create', 'show'])->names([
        'index' => 'sellers.products.index',
        'store' => 'sellers.products.store',
        'update' => 'sellers.products.update',
        'destroy' => 'sellers.products.destroy',
    ]);
});

Route::group(['prefix' => 'products/{product}'], function () {
    Route::apiResource('transactions', 'App\Http\Controllers\Product\ProductTransactionController')->only(['index'])->names([
        'index' => 'products.transactions.index'
    ]);
    Route::apiResource('buyers', 'App\Http\Controllers\Product\ProductBuyerController')->only(['index'])->names([
        'index' => 'products.buyers.index'
    ]);
    Route::apiResource('categories', 'App\Http\Controllers\Product\ProductCategoryController')->only(['index', 'update', 'destroy'])->names([
        'index' => 'products.categories.index',
        'update' => 'products.categories.update',
        'destroy' => 'products.categories.destroy',
    ]);
});

Route::post('products/{product}/buyers/{buyer}/transactions', [ProductBuyerTransactionController::class, 'store']);

Route::get('users/verify/{token}',  [UserController::class, 'verify'])->name('verify');
Route::get('users/{user}/resend',  [UserController::class, 'resend'])->name('resend');

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
Route::post('login', 'UserController@login')->name('login');