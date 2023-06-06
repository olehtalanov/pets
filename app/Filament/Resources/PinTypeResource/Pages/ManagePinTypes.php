<?php

namespace App\Filament\Resources\PinTypeResource\Pages;

use App\Filament\Resources\PinTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePinTypes extends ManageRecords
{
    protected static string $resource = PinTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
