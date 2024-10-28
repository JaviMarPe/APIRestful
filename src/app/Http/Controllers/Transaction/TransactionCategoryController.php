<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Transaction $transaction)
    {
        Log::info('Transaction ID: ' . $transaction->id);
    
        if (!$transaction->product) {
            return $this->errorResponse('Product not found for this transaction', 404);
        }

        Log::info('Product ID: ' . $transaction->product->id);

        $categories = $transaction->product->categories()->get();

        if ($categories->isEmpty()) {
            return $this->errorResponse('No categories found for this product', 404);
        }

        return $this->successResponse($categories, 200);
    }

}
