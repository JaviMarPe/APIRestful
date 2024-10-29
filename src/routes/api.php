<?php

use App\Http\Controllers\Buyer\BuyerCategoryController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Buyer\BuyerProductsController;
use App\Http\Controllers\Buyer\BuyerSellerController;
use App\Http\Controllers\Buyer\BuyerTransactionController;
use App\Http\Controllers\Category\CategoryBuyerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Category\CategoryProductController;
use App\Http\Controllers\Category\CategorySellerController;
use App\Http\Controllers\Category\CategoryTransactionController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Transaction\TransactionCategoryController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Transaction\TransactionSellerController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*
    Users
*/
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/{user}', 'show');
    Route::post('/users', 'store');
    Route::patch('/users/{user}', 'update');
    Route::delete('/users/{user}', 'destroy');
});

/*
    Categories
*/
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index');
    Route::get('/categories/{category}', 'show');
    Route::post('/categories', 'store');
    Route::patch('/categories/{category}', 'update');
    Route::delete('/categories/{category}', 'destroy');
});
Route::get('/categories/{category}/products', [CategoryProductController::class, 'index']);
Route::get('/categories/{category}/sellers', [CategorySellerController::class, 'index']);
Route::get('/categories/{category}/transactions', [CategoryTransactionController::class, 'index']);
Route::get('/categories/{category}/buyers', [CategoryBuyerController::class, 'index']);

/*
    Products
*/
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/{product}', 'show');
});

/*
    Transactions
*/
Route::controller(TransactionController::class)->group(function () {
    Route::get('/transactions', 'index');
    Route::get('/transactions/{transaction}', 'show');
});
Route::get('/transactions/{transaction}/categories', [TransactionCategoryController::class, 'index']);
Route::get('/transactions/{transaction}/sellers', [TransactionSellerController::class, 'index']);

/*
    Buyers
*/
Route::controller(BuyerController::class)->group(function () {
    Route::get('/buyers', 'index');
    Route::get('/buyers/{buyer}', 'show');
});
Route::get('/buyers/{buyer}/transactions', [BuyerTransactionController::class, 'index']);
Route::get('/buyers/{buyer}/products', [BuyerProductsController::class, 'index']);
Route::get('/buyers/{buyer}/sellers', [BuyerSellerController::class, 'index']);
Route::get('/buyers/{buyer}/categories', [BuyerCategoryController::class, 'index']);

/*
    Sellers
*/
Route::resource('sellers', SellerController::class)->only(['index', 'show']);
