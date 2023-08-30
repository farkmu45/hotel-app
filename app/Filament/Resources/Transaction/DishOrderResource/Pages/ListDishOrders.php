<?php

namespace App\Filament\Resources\Transaction\DishOrderResource\Pages;

use App\Filament\Resources\Transaction\DishOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDishOrders extends ListRecords
{
    protected static string $resource = DishOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
