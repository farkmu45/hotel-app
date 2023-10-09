<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLaundryOrder;
use App\Models\Customer;
use App\Models\Laundry;
use App\Models\LaundryType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public  static function createLaundryOrder(CreateLaundryOrder $request)
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
}
