<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductTransactionController extends ApiController
{
    public function __construct() 
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $this->allowedAdminGate();
        
        $transactions = $product->transactions()->get();

        if ($transactions->isEmpty()) {
            return $this->errorResponse('No transactions found for this product : '.$product->name, 406);
        }

        return $this->showAll($transactions, 200);
    }
}
