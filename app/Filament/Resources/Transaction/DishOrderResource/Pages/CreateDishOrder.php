<?php

namespace App\Filament\Resources\Transaction\DishOrderResource\Pages;

use App\Filament\Resources\Transaction\DishOrderResource;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;

class CreateDishOrder extends CreateRecord
{
    protected static string $resource = DishOrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['room_id'] = Customer::find($data['customer_id'])->activeOrder()->room_id;

        return $data;
    }
}
