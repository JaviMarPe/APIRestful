<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaction = Transaction::all();
        return $this->showAll($transaction, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return $this->successResponse($transaction, 200);
    }

}
