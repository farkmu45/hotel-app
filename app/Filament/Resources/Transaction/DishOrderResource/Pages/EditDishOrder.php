<?php

namespace App\Filament\Resources\Transaction\DishOrderResource\Pages;

use App\Filament\Resources\Transaction\DishOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDishOrder extends EditRecord
{
    protected static string $resource = DishOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
