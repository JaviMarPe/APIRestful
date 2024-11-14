<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductBuyerController extends ApiController
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
        
        $buyers = $product->transactions()
                ->with('buyer')
                ->get()
                ->pluck('buyer')
                ->unique('id')
                ->values();

        if ($buyers->isEmpty()) {
            return $this->errorResponse('No buyers found for this product : '.$product->name, 406);
        }

        return $this->showAll($buyers, 200);
    }
}
