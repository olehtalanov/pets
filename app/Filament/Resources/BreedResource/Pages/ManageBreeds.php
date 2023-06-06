<?php

namespace App\Filament\Resources\BreedResource\Pages;

use App\Filament\Resources\BreedResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBreeds extends ManageRecords
{
    protected static string $resource = BreedResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
