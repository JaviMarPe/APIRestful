<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
        $products = $seller->products()->get();

        if($products->isEmpty()){
            return $this->errorResponse('Not products found for this Seller', 404);
        }

        return $this->successResponse($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'quantity' => ['required', 'min:1', 'integer'],
                'image' => ['required', 'image']
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 420);
            }

            $data = $request->all();
            $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
            $data['image'] = '1.jpg';
            $data['seller_id'] = $user->id;

            $newProduct = Product::create($data);
    
            return $this->successResponse($newProduct, 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th, 423);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'quantity' => ['min:1', 'integer'],
                'status' => 'in: '.Product::PRODUCTO_DISPONIBLE.','.Product::PRODUCTO_NO_DISPONIBLE,
                'image' => ['image']
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 420);
            }

            $this->verifiedSeller($seller, $product);

            $product->fill($request->only([
                'name',
                'description',
                'quantity'
            ]));

            if($request->has('status')){
                $product->status = $request->status;
                if($product->estaDisponible() && $product->categories()->count() == 0){
                    return $this->errorResponse('El producto activo debe tener al menos una categoria', 409);
                }
            }

            if($product->isClean()){
                return response()->json(['error' => 'Se debe especificar al menos un valor diferente para actualizar'], 422);
            } 

            $product->save();
    
            return $this->successResponse($product, 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th, 423);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verifiedSeller($seller, $product);

        $product->delete();

        return $this->successResponse($product);
    }

    /*
        Verificamos si el producto que esta manejando el vendedor es suyo
    */
    protected function verifiedSeller(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id){
            throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');
        }
    }
}
