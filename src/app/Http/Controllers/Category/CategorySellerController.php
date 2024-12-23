<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategorySellerController extends ApiController
{
    public function __construct() 
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        $sellers = $category->products()
                    ->with('seller')
                    ->get()
                    ->pluck('seller')
                    ->unique('id')
                    ->values();

        if($sellers->isEmpty()){
            return $this->errorResponse('Not sellers found for this Category', 404);
        }

        return $this->showAll($sellers, 200);
    }
}
