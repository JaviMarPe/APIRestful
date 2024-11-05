<?php

namespace App\Models;

use App\Models\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends User
{
    use HasFactory;

    public $transformer = BuyerTransformer::class;

    /**
     * The "booted" method of the model.
     */
    /*
    Este Global Scope es un añadido por defectoa  la query cuando se vayan a retorna los elementos de Buyer.
    Lo hacemos por que los Buyer sson User que tienen transacciones, sino serian solo usuarios.
    */
    protected static function booted(): void
    {
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions(): HasMany{
        return $this->hasMany(Transaction::class);
    }
}
