<?php

namespace App\Transformers;

use App\Models\Category;
use Illuminate\Support\Facades\Route;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
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
    public function transform(Category $category)
    {
        return [
            'id' => (int)$category->id,
            'title' => (string)$category->name,
            'details' => (string)$category->description,
            'createdDate' => (string)$category->created_at,
            'updatedDate' => (string)$category->update_at,
            'deletedDate' => isset($category->deleted_at) ? (string)$category->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id) 
                ],
                [
                    'rel' => 'category.buyers',
                    'href' => route('categories.buyers.index', ['category' => $category->id]) 
                ],
                [
                    'rel' => 'category.products',
                    'href' => route('categories.products.index', ['category' => $category->id]) 
                ],
                [
                    'rel' => 'category.sellers',
                    'href' => route('categories.sellers.index', $category->id) 
                ],
                [
                    'rel' => 'category.transactions',
                    'href' => route('categories.transactions.index', $category->id) 
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
            'createdDate' => 'created_at',
            'updatedDate' => 'update_at',
            'deletedDate' => 'deleted_at'
        ];

        return isset($attribute[$index])?$attribute[$index]:null;
    }
}
