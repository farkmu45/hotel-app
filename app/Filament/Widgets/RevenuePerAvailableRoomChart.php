<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Room;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RevenuePerAvailableRoomChart extends ApexChartWidget
{
    protected static string $chartId = 'revenuePerAvailableRoomChart';

    protected static ?string $heading = 'Revenue Per Available Room';

    protected int|string|array $columnSpan = 'full';

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
                ->label('Tanggal Awal')
                ->native(false)
                ->maxDate(fn (Get $get) => $get('date_end'))
                ->default(now()->subDays(7))
                ->afterStateUpdated(function () {
                    $this->updateOptions();
                })
                ->reactive(),
            DatePicker::make('date_end')
                ->label('Tanggal Akhir')
                ->minDate(fn (Get $get) => $get('date_start'))
                ->native(false)
                ->reactive()
                ->afterStateUpdated(function () {
                    $this->updateOptions();
                })
                ->default(now()),

        ];
    }

    protected function getOptions(): array
    {
        $dateStart = $this->filterFormData['date_start'];
        $dateEnd = $this->filterFormData['date_end'];

        $period = CarbonPeriod::create($dateStart, $dateEnd);
        $data = [];

        $roomCount = Room::count();

        foreach ($period as $date) {
            $date = $date->format('Y-m-d');

            $orders = Order::where('check_in_date', $date)
                ->whereNotNull('check_in');

            $totalRevenue = $orders->sum('price');
            $occupiedRooms = $orders->count();

            $rate = $totalRevenue > 0 ? $totalRevenue / ($roomCount - $occupiedRooms) : 0;
            array_push($data, round($rate, 2));
        }

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Revenue',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => iterator_to_array($period->map(fn ($date) => $date->format('m/d'))),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
        ];
    }
}
