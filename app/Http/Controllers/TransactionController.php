<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLaundryOrder;
use App\Http\Requests\CreateOrder;
use App\Models\Customer;
use App\Models\Laundry;
use App\Models\LaundryType;
use App\Models\Order;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public static function createLaundryOrder(CreateLaundryOrder $request)
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

    public  static function createOrder(CreateOrder $request)
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
}
