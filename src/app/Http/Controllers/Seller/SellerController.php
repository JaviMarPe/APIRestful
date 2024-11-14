<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    public function __construct() 
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only(['show']);
        $this->middleware('can:view,seller')->only(['show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->allowedAdminGate();
        
        $sellers = Seller::has('products')->get();
        return $this->showAll($sellers, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        return response()->json(['data' => $seller], 200);
    }
}
