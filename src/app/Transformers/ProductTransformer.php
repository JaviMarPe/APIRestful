<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id' => (int)$product->id,
            'title' => (string)$product->name,
            'details' => (string)$product->description,
            'available' => (int)$product->quantity,
            'status' => (string)$product->status,
            'img' => url("img/{$product->image}"),
            'seller' => (int)$product->seller_id,
            'createdDate' => (string)$product->created_at,
            'updatedDate' => (string)$product->update_at,
            'deletedDate' => isset($product->deleted_at) ? (string)$product->deleted_at : null,
        ];
    }
}
