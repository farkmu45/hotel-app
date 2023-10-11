<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDishOrder;
use App\Http\Requests\CreateLaundryOrder;
use App\Http\Requests\CreateOrder;
use App\Http\Resources\DishOrderCollection;
use App\Http\Resources\LaundryOrderCollection;
use App\Http\Resources\OrderCollection;
use App\Models\Customer;
use App\Models\Dish;
use App\Models\DishOrder;
use App\Models\DishOrderItem;
use App\Models\Laundry;
use App\Models\LaundryType;
use App\Models\Order;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function createLaundryOrder(CreateLaundryOrder $request)
    {
        $data = $request->validated();

        $customerId = $data['customer_id'];

        $customer = Customer::where('id', $customerId)
            ->whereIn(
                'id',
                fn ($q) => $q->select('customer_id')
                    ->from('orders')
                    ->where('check_out', null)
                    ->whereNotNull('check_in')
            )->first();

        if (!$customer) {
            throw ValidationException::withMessages(['Pelanggan ini belum memesan kamar']);
        }

        $data['room_id'] = $customer->activeOrder()->room_id;
        $data['price'] = LaundryType::find($data['laundry_type_id'])->price * $data['weight'];

        return ['data' => Laundry::create($data)];
    }

    public function createOrder(CreateOrder $request)
    {
        $data = $request->validated();

        $customerId = $data['customer_id'];

        $customer = Customer::where('id', $customerId)
            ->whereIn(
                'id',
                fn ($q) => $q->select('customer_id')
                    ->from('orders')
                    ->where('check_out', null)
            )->first();

        if ($customer) {
            throw ValidationException::withMessages(['Pelanggan ini sudah memesan kamar']);
        }

        $room = Room::where('room_type_id', $data['room_type_id'])
            ->whereNotIn(
                'id',
                fn ($q) => $q->select('room_id')->from('orders')
                    ->where('check_out_date', '>', $data['check_in_date'])
            )
            ->first();

        if (!$room) {
            throw ValidationException::withMessages(['Tidak ada kamar yang tersedia untuk tipe kamar yang dipilih']);
        }

        $stayLength = Carbon::parse($data['check_in_date'])
            ->startOfDay()
            ->diffInDays(
                Carbon::parse($data['check_out_date'])->startOfDay()
            );

        $price = $room->roomType->rates * $stayLength;

        $data['room_id'] = $room->id;
        $data['price'] = $price;

        unset($data['room_type_id']);

        return Order::create($data);
    }

    public function createDishOrder(CreateDishOrder $request)
    {
        $data = $request->validated();

        $customerId = $data['customer_id'];

        $customer = Customer::where('id', $customerId)
            ->whereIn(
                'id',
                fn ($q) => $q->select('customer_id')
                    ->from('orders')
                    ->where('check_out', null)
                    ->whereNotNull('check_in')
            )->first();

        if (!$customer) {
            throw ValidationException::withMessages(['Pelanggan ini belum memesan kamar']);
        }

        $order = DishOrder::create([
            'customer_id' => $customerId,
            'room_id' => Customer::find($customerId)->activeOrder()->room->id,
        ]);

        foreach ($data['order'] as $item) {
            $dish = Dish::find($item['dish_id']);

            DishOrderItem::create([
                'dish_id' => $item['dish_id'],
                'dish_order_id' => $order->id,
                'qty' => $item['qty'],
                'price_per_item' => $dish->price,
                'price' => $dish->price * $item['qty'],
            ]);
        }

        return $order;
    }

    public  function listLaundryOrder()
    {
        return new LaundryOrderCollection(Laundry::latest()->get());
    }

    public  function listOrder()
    {
        return new OrderCollection(Order::latest()->get());
    }

    public  function listDishOrder()
    {
        return new DishOrderCollection(DishOrder::latest()->get());
    }
}
