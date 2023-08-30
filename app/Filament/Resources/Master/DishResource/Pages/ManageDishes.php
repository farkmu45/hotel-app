<?php

namespace App\Filament\Resources\Master\DishResource\Pages;

use App\Filament\Resources\Master\DishResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDishes extends ManageRecords
{
    protected static string $resource = DishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
