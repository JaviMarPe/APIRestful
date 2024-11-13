<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BuyerProductsController extends ApiController
{
    public function __construct() 
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Buyer $buyer)
    {
        Log::info('Buyer Name : '.$buyer->name);

        if(!$buyer->transactions){
            return $this->errorResponse('This buyer '.$buyer->name.' does not have any transaction', 404);
        }

        $products = $buyer->transactions()->with('product')->get()->pluck('product');

        Log::info(json_encode($products));
        if($products->isEmpty()){
            return $this->errorResponse('Not products found for this Buyer', 404);
        }

        return $this->showAll($products, 200);
    }
}
