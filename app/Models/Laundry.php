<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Laundry extends Model
{
    use HasFactory;

    public function laundryType(): BelongsTo
    {
        return $this->belongsTo(LaundryType::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    protected static function booted()
    {
        static::creating(fn (Laundry $laundry) => $laundry->code = 'L'.random_int(100000, 999999));

        static::created(fn (Laundry $laundry) => $laundry->transaction()->create());

        static::deleted(fn (Laundry $laundry) => $laundry->transaction()->delete());
    }
}
