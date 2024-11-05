<?php

namespace App\Transformers;

use App\Models\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
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
    public function transform(Seller $seller)
    {
        return [
            'id' => (int)$seller->id,
            'name' => (string)$seller->name,
            'email' => (string)$seller->id,
            'isVerified' => (int)$seller->verified,
            'createdDate' => (string)$seller->created_at,
            'updatedDate' => (string)$seller->update_at,
            'deletedDate' => isset($seller->deleted_at) ? (string)$seller->deleted_at : null,
        ];
    }
}
