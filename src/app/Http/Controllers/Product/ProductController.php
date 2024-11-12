<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    public function __construct() 
    {
        //parent::__construct();
        $this->middleware('client.credential')->only(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::all();
        return $this->showAll($product, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->successResponse($product, 200);
    }
    
}
