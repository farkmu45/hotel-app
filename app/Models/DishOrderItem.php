<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DishOrderItem extends Model
{
    public $timestamps = false;

    public function dishOrder(): BelongsTo
    {
        return $this->belongsTo(DishOrder::class);
    }

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}
