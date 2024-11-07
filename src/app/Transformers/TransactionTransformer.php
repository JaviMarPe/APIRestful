<?php

namespace App\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
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
    public function transform(Transaction $transaction)
    {
        return [
            'id' => (int)$transaction->id,
            'quantity' => (int)$transaction->quantity,
            'buyer' => (int)$transaction->buyer_id,
            'product' => (int)$transaction->product_id,
            'createdDate' => (string)$transaction->created_at,
            'updatedDate' => (string)$transaction->update_at,
            'deletedDate' => isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $transaction->id) 
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', ['buyer' => $transaction->buyer_id]) 
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', ['product' => $transaction->product_id]) 
                ],
                [
                    'rel' => 'transaction.categories',
                    'href' => route('transactions.categories.index', ['transaction' => $transaction->id]) 
                ],
                [
                    'rel' => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $transaction->id) 
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'id' => 'id',
            'quantity' => 'quantity',
            'buyer' => 'buyer_id',
            'product' => 'product_id',
            'createdDate' => 'created_at',
            'updatedDate' => 'update_at',
            'deletedDate' => 'deleted_at'
        ];

        return isset($attribute[$index])?$attribute[$index]:null;
    }
}
