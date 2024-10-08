<?php

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

/*Route::get('/', function (Request $request) {
    return view('welcome');
});*/

/*
    Buyers
*/
Route::resource('buyers', 'Buyer\BuyerController', [ 'only' => ['index', 'show']]);

/*
    Categories
*/
Route::resource('categories', 'Category\CategoryController', [ 'except' => ['create', 'edit']]);

/*
    Products
*/
Route::resource('products', 'Product\ProductController', [ 'only' => ['index', 'show']]);

/*
    Transactions
*/
Route::resource('transactions', 'Transaction\TransactionController', [ 'only' => ['index', 'show']]);

/*
    Sellers
*/
Route::resource('sellers', 'Seller\SellerController', [ 'only' => ['index', 'show']]);

/*
    Users
*/
Route::resource('users', 'User\UserController', [ 'except' => ['create', 'edit']]);