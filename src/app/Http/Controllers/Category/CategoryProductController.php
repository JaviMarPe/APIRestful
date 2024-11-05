<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        $products = $category->products;

        if($products->isEmpty()){
            return $this->errorResponse('Not products found for this Category ', 404);
        }

        return $this->showAll($products, 200);
    }
}
