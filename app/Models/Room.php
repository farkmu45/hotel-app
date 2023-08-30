<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public static function booted(): void
    {
        static::creating(fn (Room $room) => $room->id = 'R'.random_int(100000, 999999));
    }
}
