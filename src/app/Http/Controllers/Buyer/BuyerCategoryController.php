<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Buyer $buyer)
    {
        if(!$buyer->transactions){
            return $this->errorResponse('This buyer '.$buyer->name.' does not have any transaction', 404);
        }

        $categories = $buyer->transactions()
                    ->with('product.categories')
                    ->get()
                    ->pluck('product.categories')//solo te muestra los seller, los productos y las transacciones las "elimina" de la respuesta
                    ->collapse() //transforma un array de varios niveles en una sola lista de colecciones
                    ->unique('id')//Para que no se repitan
                    ->values();

        if($categories->isEmpty()){
            return $this->errorResponse('Not categories found for this Buyer', 404);
        }

        return $this->successResponse($categories, 200);
    }
}