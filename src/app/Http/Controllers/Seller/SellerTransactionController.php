<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerTransactionController extends ApiController
{
    public function __construct() 
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
        $transactions = $seller->products()
                ->whereHas('transactions')
                ->with('transactions')
                ->get()
                ->pluck('transactions')
                ->collapse();

        if($transactions->isEmpty()){
            return $this->errorResponse('Not transactions found for this Seller', 404);
        }

        return $this->showAll($transactions, 200);
    }
}
