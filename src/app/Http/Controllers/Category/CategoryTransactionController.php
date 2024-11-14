<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
{
    public function __construct() 
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        $this->allowedAdminGate();
        
        $transactions = $category->products()
                ->whereHas('transactions') //Si los productos no tienen transacciones no sera incluidos en la respuesta
                ->with('transactions')
                ->get()
                ->pluck('transactions')
                ->collapse();

        if($transactions->isEmpty()){
            return $this->errorResponse('Not transactions found for this Category', 404);
        }

        return $this->showAll($transactions, 200);
    }
}
