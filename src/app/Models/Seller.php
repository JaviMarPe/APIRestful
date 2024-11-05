<?php

namespace App\Models;

use App\Models\Scopes\SellerScope;
use App\Transformers\SellerTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends User
{
    use HasFactory;

    public $transformer = SellerTransformer::class;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new SellerScope);
    }

    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }
}
