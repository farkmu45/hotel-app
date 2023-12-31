<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Room;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class HotelOccupancyRateChart extends ApexChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static string $chartId = 'hotelOccupancyRateChart';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Hotel Occupancy Rate';

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

            $orders = Order::where('check_in_date', '<=', $date)
                ->where('check_out_date', '>=', $date)
                ->whereNotNull('check_in');

            $occupiedRoom = $orders->count();

            $rate = $occupiedRoom > 0 ? ($occupiedRoom / $roomCount) * 100 : 0;
            array_push($data, round($rate, 2));
        }

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Rate',
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
            // 'stroke' => [
            //     'curve' => 'smooth',
            // ],
        ];
    }
}
