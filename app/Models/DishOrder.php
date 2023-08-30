<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class DishOrder extends Model
{
    protected static function booted()
    {
        static::creating(fn (DishOrder $dishOrder) => $dishOrder->code = 'O'.random_int(100000, 999999));

        static::created(fn (DishOrder $dishOrder) => $dishOrder->transaction()->create());

        static::saved(function (DishOrder $dishOrder) {
            $total = $dishOrder->items->sum('price');
            $dishOrder->price = $total;
            $dishOrder->save();
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DishOrderItem::class);
    }

    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
