<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionCategoryController extends ApiController
{
    public function __construct() 
    {
        $this->middleware('client.credential')->only(['index']);
        $this->middleware('scope:read-general')->only(['show']);
    }
    /**
     * Display a information of transaction and the categories related to transaction
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

        return $this->showAll($categories, 200);
    }

}
