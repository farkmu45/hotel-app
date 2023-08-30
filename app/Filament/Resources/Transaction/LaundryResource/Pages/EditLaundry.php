<?php

namespace App\Filament\Resources\Transaction\LaundryResource\Pages;

use App\Filament\Resources\Transaction\LaundryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaundry extends EditRecord
{
    protected static string $resource = LaundryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
