<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Buyer $buyer)
    {
        if(!$buyer->transactions){
            return $this->errorResponse('This buyer '.$buyer->name.' does not have any transaction', 404);
        }

        $sellers = $buyer->transactions()
                    ->with('product.seller')
                    ->get()
                    ->pluck('product.seller')//solo te muestra los seller, los productos y las transacciones las "elimina" de la respuesta
                    ->unique('id')//Para que no se repitan
                    ->values();

        if($sellers->isEmpty()){
            return $this->errorResponse('Not sellers found for this Buyer', 404);
        }

        return $this->showAll($sellers, 200);
    }

}
