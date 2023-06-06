<?php

namespace App\Filament\Resources\AnimalTypeResource\Pages;

use App\Filament\Resources\AnimalTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAnimalTypes extends ManageRecords
{
    protected static string $resource = AnimalTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
