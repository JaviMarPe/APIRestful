<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $buyers = $product->transactions()
                ->with('buyer')
                ->get()
                ->pluck('buyer')
                ->unique('id')
                ->values();

        if ($buyers->isEmpty()) {
            return $this->errorResponse('No buyers found for this product : '.$product->name, 406);
        }

        return $this->successResponse($buyers, 200);
    }
}
