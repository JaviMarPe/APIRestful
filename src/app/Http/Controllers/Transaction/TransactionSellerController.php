<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionSellerController extends ApiController
{
    public function __construct() 
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only(['index']);
        //policies para restriguir las acciones del comprador a ver el vendedor/detalles de la transaccion de la compra
        $this->middleware('can:view,transaction')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Transaction $transaction)
    {
        Log::info('Transaction ID: ' . $transaction->id);
    
        if (!$transaction->product) {
            return $this->errorResponse('Product not found for this transaction', 404);
        }

        Log::info('Product ID: ' . $transaction->product->id);

        $seller = $transaction->product->seller()->get();

        if ($seller->isEmpty()) {
            return $this->errorResponse('No seller found for this product', 404);
        }

        return $this->showAll($seller, 200);
    }

}
