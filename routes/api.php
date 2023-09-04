<?php

use App\Http\Controllers\MetricController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('hotel-occupancy-rate', [MetricController::class, 'hotelOccupancyRate']);
Route::get('hotel-average-daily-rate', [MetricController::class, 'hotelAverageDailyRate']);
Route::get('revenue-per-available-room', [MetricController::class, 'revenuePerAvailableRoom']);
