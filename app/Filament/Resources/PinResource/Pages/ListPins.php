<?php

namespace App\Filament\Resources\PinResource\Pages;

use App\Filament\Resources\PinResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPins extends ListRecords
{
    protected static string $resource = PinResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
