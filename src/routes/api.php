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
    Route::apiResource('products', 'App\Http\Controllers\Category\CategoryProductController')->only(['index']);
    Route::apiResource('sellers', 'App\Http\Controllers\Category\CategorySellerController')->only(['index']);
    Route::apiResource('transactions', 'App\Http\Controllers\Category\CategoryTransactionController')->only(['index']);
    Route::apiResource('buyers', 'App\Http\Controllers\Category\CategoryBuyerController')->only(['index']);
});

Route::group(['prefix' => 'transactions/{transaction}'], function () {
    Route::apiResource('categories', 'App\Http\Controllers\Transaction\TransactionCategoryController')->only(['index']);
    Route::apiResource('sellers', 'App\Http\Controllers\Transaction\TransactionSellerController')->only(['index']);
});

Route::group(['prefix' => 'buyers/{buyer}'], function () {
    Route::apiResource('transactions', 'App\Http\Controllers\Buyer\BuyerTransactionController')->only(['index']);
    Route::apiResource('products', 'App\Http\Controllers\Buyer\BuyerProductsController')->only(['index']);
    Route::apiResource('sellers', 'App\Http\Controllers\Buyer\BuyerSellerController')->only(['index']);
    Route::apiResource('categories', 'App\Http\Controllers\Buyer\BuyerCategoryController')->only(['index']);
});

Route::group(['prefix' => 'sellers/{seller}'], function () {
    Route::apiResource('transactions', 'App\Http\Controllers\Seller\SellerTransactionController')->only(['index']);
    Route::apiResource('categories', 'App\Http\Controllers\Seller\SellerCategoryController')->only(['index']);
    Route::apiResource('buyers', 'App\Http\Controllers\Seller\SellerBuyerController')->only(['index']);
    Route::apiResource('products', 'App\Http\Controllers\Seller\SellerProductController')->except(['edit', 'create', 'show']);
});

Route::group(['prefix' => 'products/{product}'], function () {
    Route::apiResource('transactions', 'App\Http\Controllers\Product\ProductTransactionController')->only(['index']);
    Route::apiResource('buyers', 'App\Http\Controllers\Product\ProductBuyerController')->only(['index']);
    Route::apiResource('categories', 'App\Http\Controllers\Product\ProductCategoryController')->only(['index', 'update', 'destroy']);
});

Route::post('products/{product}/buyers/{buyer}/transactions', [ProductBuyerTransactionController::class, 'store']);

Route::get('users/verify/{token}',  [UserController::class, 'verify'])->name('verify');
Route::get('users/{user}/resend',  [UserController::class, 'resend'])->name('resend');
