<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerBuyerController extends ApiController
{
    public function __construct() 
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
        $this->allowedAdminGate();
        
        $buyers = $seller->products()
                    ->whereHas('transactions') //el producto tiene que tener transacciones
                    ->with('transactions.buyer') //queremos la info de las transaccion y los buyers relaciones de esas transacciones
                    ->get()
                    ->flatMap(fn ($product) => $product->transactions->pluck('buyer'))
                    ->unique('id') //nos aseguramos de no repetir valores
                    ->values();

        if($buyers->isEmpty()){
            return $this->errorResponse('No buyers found for this Seller', 404);
        }

        return $this->showAll($buyers);
    }
}
