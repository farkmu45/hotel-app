<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Master\DishResource;
use App\Http\Resources\DishCollection;
use App\Http\Resources\LaundryTypeCollection;
use App\Http\Resources\PurchasableItemCollection;
use App\Http\Resources\RoomTypeCollection;
use App\Http\Resources\UserCollection;
use App\Models\Customer;
use App\Models\Dish;
use App\Models\LaundryType;
use App\Models\RoomType;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function getCustomers(Request $request)
    {

        $inOrders = $request->query('in_orders', true);
        $users = null;

        if ($inOrders) {
            $users = Customer::whereNotIn(
                'id',
                fn ($q) => $q->select('customer_id')
                    ->from('orders')
                    ->where('check_out', null)
                    ->orWhere('check_in', null)
            )->get();
        } else {
            $users = Customer::all();
        }

        return new UserCollection($users);
    }

    public function getDishes()
    {
        $dishes = Dish::all();
        return new PurchasableItemCollection($dishes);
    }

    public function getLaundryTypes()
    {
        $laundryTypes = LaundryType::all();
        return new PurchasableItemCollection($laundryTypes);
    }

    public function getRoomTypes()
    {
        $roomTypes = RoomType::all();
        return new RoomTypeCollection($roomTypes);
    }
}
