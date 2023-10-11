<?php

use App\Http\Controllers\MasterController;
use App\Http\Controllers\MetricController;
use App\Http\Controllers\TransactionController;
use App\Models\Transaction;
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

Route::get('master/customers', [MasterController::class, 'getCustomers']);
Route::get('master/dishes', [MasterController::class, 'getDishes']);
Route::get('master/laundry-types', [MasterController::class, 'getLaundryTypes']);
Route::get('master/room-types', [MasterController::class, 'getRoomTypes']);

Route::post('transaction/laundry', [TransactionController::class, 'createLaundryOrder']);
Route::post('transaction/orders', [TransactionController::class, 'createOrder']);
