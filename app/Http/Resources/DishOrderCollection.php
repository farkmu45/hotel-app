<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DishOrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(
            fn ($order) => [
                'code' => $order->code,
                'customer' => $order->customer->name,
                'room' => $order->room_id,
                'price' => $order->items->sum('price')
            ]
        )->toArray();
    }
}
