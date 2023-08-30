<?php

namespace App\Filament\Resources\Transaction\LaundryResource\Pages;

use App\Filament\Resources\Transaction\LaundryResource;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;

class CreateLaundry extends CreateRecord
{
    protected static string $resource = LaundryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['room_id'] = Customer::find($data['customer_id'])->activeOrder()->room_id;

        return $data;
    }
}
