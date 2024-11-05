<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
