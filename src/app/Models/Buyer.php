<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends User
{
    use HasFactory;

    public function transactions(): HasMany{
        return $this->hasMany(Transaction::class);
    }
}
