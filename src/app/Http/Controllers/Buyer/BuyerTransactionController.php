<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Buyer $buyer)
    {
        Log::info('Buyer ID : '.$buyer->id);
        
        $transactions = $buyer->transactions()->get();

        if($transactions->isEmpty()){
            return $this->errorResponse('No transactions found for this buyer', 404);
        }

        return $this->successResponse($transactions, 200);
    }
}
