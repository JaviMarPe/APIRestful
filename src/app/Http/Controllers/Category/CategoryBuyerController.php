<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Transformers\BuyerTransformer;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        /*En este caso, al haber varias transacciones dentro de los productor(relacion hasMany) entonces tenemos que hacer primero 
        el pluck de la coleccion de transacciones y luego de buyer*/
        $buyers = $category->products()//comenzamos obteniendo los productos
                ->whereHas('transactions') //Si los productos no tienen transacciones no sera incluidos en la respuesta
                ->with('transactions.buyer')//requerimos en la lista de transacciones el comprador, ya que es una relacion compuesta
                ->get()//obtenemos el resultado
                ->pluck('transactions')//obtenemos todo los valores con la clave transaction
                ->collapse()//unimos todas las colecciones que obtenemos en una sola collecion, para evitar conflictos
                ->pluck('buyer')//obtenemos todo los valores con la clave buyer
                ->unique()//para que no obtener compradores repetidos
                ->values();//eliminamos los elemntos vacios

        if($buyers->isEmpty()){
            return $this->errorResponse('Not buyers found for this Category', 404);
        }

        // Ensure each buyer has the transformer property set
        $buyers = $buyers->map(function ($buyer) {
            $buyer->transformer = BuyerTransformer::class;
            return $buyer;
        });

        return $this->showAll($buyers, 200);
    }

}
