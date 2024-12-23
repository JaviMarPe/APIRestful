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
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id) 
                ],
                [
                    'rel' => 'product.buyers',
                    'href' => route('products.buyers.index', ['product' => $product->id]) 
                ],
                [
                    'rel' => 'product.categories',
                    'href' => route('products.categories.index', ['product' => $product->id]) 
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $product->seller_id) 
                ],
                [
                    'rel' => 'product.transactions',
                    'href' => route('products.transactions.index', $product->id) 
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'id' => 'id',
            'title' => 'name',
            'details' => 'description',
            'available' => 'quantity',
            'status' => 'status',
            'img' => 'image',
            'seller' => 'seller_id',
            'createdDate' => 'created_at',
            'updatedDate' => 'update_at',
            'deletedDate' => 'deleted_at'
        ];

        return isset($attribute[$index])?$attribute[$index]:null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'id' => 'id',
            'name' => 'title',
            'description' => 'details',
            'quantity' => 'available',
            'status' => 'status',
            'image' => 'img',
            'seller_id' => 'seller',
            'isVerified' => 'verified',
            'created_at' => 'createdDate',
            'update_at' => 'updatedDate',
            'deleted_at' => 'deletedDate',
        ];

        return isset($attribute[$index])?$attribute[$index]:null;
    }
}
