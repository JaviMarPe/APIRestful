<?php

namespace App\Models;

use App\Models\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends User
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions(): HasMany{
        return $this->hasMany(Transaction::class);
    }
}
