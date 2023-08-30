<?php

namespace App\Filament\Resources\Master\LaundryTypeResource\Pages;

use App\Filament\Resources\Master\LaundryTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLaundryTypes extends ManageRecords
{
    protected static string $resource = LaundryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
