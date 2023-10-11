<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LaundryOrderCollection extends ResourceCollection
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
                'type' => $order->laundryType->name,
                'weight' => $order->weight,
                'price' => $order->price
            ]
        )->toArray();;
    }
}
