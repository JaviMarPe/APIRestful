<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id',
    ];

    /**
     * Get the buyer that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buyer(): BelongsTo{
        return $this->belongsTo(Buyer::class);
    }
    
    /**
     * Get the Product that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        Log::info('Accessing product relation for transaction: ' . $this->id);
        return $this->belongsTo(Product::class);
    }
}
