<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;

class BuyerController extends ApiController
{
    public function __construct() 
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only(['show']);
        $this->middleware('can:view,buyer')->only(['show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->allowedAdminGate();
        $buyers = Buyer::has('transactions')->get();
        return $this->showAll($buyers, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Buyer $buyer)
    {
        return $this->successResponse($buyer, 200);
    }
}
