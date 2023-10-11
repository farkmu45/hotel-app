<?php

namespace App\Http\Controllers;

use App\Http\Resources\MetricResource;
use App\Models\Order;
use App\Models\Room;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class MetricController extends Controller
{
    public function getPeriod(Request $request)
    {
        $dateStart = $request->query('date_start', now()->subDays(7));
        $dateEnd = $request->query('date_end', now());

        return CarbonPeriod::create($dateStart, $dateEnd);
    }

    public function hotelOccupancyRate(Request $request)
    {
        $period = $this->getPeriod($request);
        $data = [];

        $roomCount = Room::count();

        foreach ($period as $date) {
            $date = $date->format('Y-m-d');

            $orders = Order::where('check_in_date', '<=', $date)
                ->where('check_out_date', '>=', $date)
                ->whereNotNull('check_in');

            $occupiedRoom = $orders->count();

            $rate = $occupiedRoom > 0 ? ($occupiedRoom / $roomCount) * 100 : 0;
            array_push($data, ['date' => $date, 'value' => round($rate, 2)]);
        }

        return new MetricResource($data);
    }

    public function hotelAverageDailyRate(Request $request)
    {
        $period = $this->getPeriod($request);
        $data = [];

        foreach ($period as $date) {
            $date = $date->format('Y-m-d');

            $orders = Order::where('check_in_date', $date)
                ->whereNotNull('check_in');

            $totalRevenue = $orders->sum('price');
            $occupiedRooms = $orders->count();

            $rate = $totalRevenue > 0 ? $totalRevenue / $occupiedRooms : 0;
            array_push($data, ['date' => $date, 'value' => round($rate, 2)]);
        }

        return new MetricResource($data);
    }

    public function revenuePerAvailableRoom(Request $request)
    {
        $period = $this->getPeriod($request);
        $data = [];

        $roomCount = Room::count();

        foreach ($period as $date) {
            $date = $date->format('Y-m-d');

            $orders = Order::where('check_in_date', $date)
                ->whereNotNull('check_in');

            $totalRevenue = $orders->sum('price');
            $occupiedRooms = $orders->count();

            $rate = $totalRevenue > 0 ? $totalRevenue / ($roomCount - $occupiedRooms) : 0;
            array_push($data, ['date' => $date, 'value' => round($rate, 2)]);
        }

        return new MetricResource($data);
    }
}
