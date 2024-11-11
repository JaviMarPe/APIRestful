<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Transformers\TransactionTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct(){
        parent::__construct();
        $this->middleware('transform.input'.TransactionTransformer::class)->only(['store']);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => ['required', 'min:1', 'integer']
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 420);
        }

        Log::info(json_encode($buyer));

        //check if the buyer is not the same as the seller
        if ($product->seller_id == $buyer->id) {
            Log::info("Buyer ID = ".$buyer->id.", SELLER ID = ".$product->seller_id);
            return $this->errorResponse('Buyer must be different than seller', 409);
        }

        if (!$buyer->esVerificado()) {
            Log::info("User verificado = ".$buyer->verified);
            return $this->errorResponse('Buyer must be verified user', 409);
        }

        if(!$product->estaDisponible()){
            return $this->errorResponse('Product is not available yet', 409);
        }

        //Si todavia hay stock del producto. Comprar la cantidad de productos con las transacciones
        if($product->quantity < $request->quantity){
            return $this->errorResponse('The product does not have the required quantity available for the transaction. Num available product is '.$product->quantity, 409);
        }

        return DB::transaction(
            function () use ($request, $product, $buyer){
                $product->quantity -= $request->quantity;
                $product->save();
                $transaction = Transaction::create([
                    'quantity' => $request->quantity,
                    'buyer_id' => $buyer->id,
                    'product_id' => $product->id
                ]);

                return $this->successResponse($transaction, 201);
            }
        );

    }

}
