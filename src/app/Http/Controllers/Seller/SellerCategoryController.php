<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerCategoryController extends ApiController
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
        $categories = $seller->products()
                    ->with('categories')
                    ->get()
                    ->pluck('categories')//solo te muestra los seller, los productos y las transacciones las "elimina" de la respuesta
                    ->collapse() //transforma un array de varios niveles en una sola lista de colecciones
                    ->unique('id')//Para que no se repitan
                    ->values();

        if($categories->isEmpty()){
            return $this->errorResponse('Not categories found for this Seller', 404);
        }

        return $this->showAll($categories, 200);
    }
}
