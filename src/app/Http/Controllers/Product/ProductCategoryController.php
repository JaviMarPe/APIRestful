<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductCategoryController extends ApiController
{
    public function __construct() 
    {
        $this->middleware('auth:api')->except(['index']);
        $this->middleware('client.credential')->only(['index']);
        $this->middleware('scope:manage-product')->except(['index']);
        //policies para restriguir que solo el vendedor puede crear o visualizar sus productos
        $this->middleware('can:add-category,product')->only(['update']);
        $this->middleware('can:delete-category,product')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        if ($categories->isEmpty()) {
            return $this->errorResponse('No categories found for this product : '.$product->name, 406);
        }

        return $this->successResponse($categories, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, Category $category)
    {
        if ($product->categories()->where('categories.id', $category->id)->exists()) {
            throw new Exception('The category is already associated with this product', 422);
        }

        //$product->categories()->syncWithPivotValues([$category->id]);
        $product->categories()->attach([$category->id]);

        $product->load('categories');

        return $this->successResponse($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Category $category)
    {
        if(!$product->categories->find($category->id)){
            Log::info("La categoria no esta asociada al producto");
            return $this->errorResponse('THe category is not related with this product : '.$product->name, 406);
        }

        $product->categories()->detach([$category->id]);

        $product->load('categories');

        return $this->successResponse($product->categories, 201);
    }
}
