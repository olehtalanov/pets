<?php

namespace App\Filament\Resources\PinResource\Pages;

use App\Filament\Resources\PinResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPin extends EditRecord
{
    protected static string $resource = PinResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
