<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        /*En este caso, al haber varias transacciones dentro de los productor(relacion hasMany) entonces tenemos que hacer primero 
        el pluck de la coleccion de transacciones y luego de buyer*/
        $buyers = $category->products()
                ->whereHas('transactions') //Si los productos no tienen transacciones no sera incluidos en la respuesta
                ->with('transactions.buyer')
                ->get()
                ->pluck('transactions')
                ->collapse()
                ->pluck('buyer')
                ->unique()
                ->values();

        if($buyers->isEmpty()){
            return $this->errorResponse('Not buyers found for this Category', 404);
        }

        return $this->successResponse($buyers, 200);
    }

}
