<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        $transactions = $category->products()
                ->has('transactions') //Si los productos no tienen transacciones no sera incluidos en la respuesta
                ->with('transactions')
                ->get()
                ->pluck('transactions')
                ->collapse();

        if($transactions->isEmpty()){
            return $this->errorResponse('Not sellers found for this Category', 404);
        }

        return $this->successResponse($transactions, 200);
    }
}
