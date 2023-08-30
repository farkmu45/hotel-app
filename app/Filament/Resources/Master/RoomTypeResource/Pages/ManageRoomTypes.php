<?php

namespace App\Filament\Resources\Master\RoomTypeResource\Pages;

use App\Filament\Resources\Master\RoomTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRoomTypes extends ManageRecords
{
    protected static string $resource = RoomTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
