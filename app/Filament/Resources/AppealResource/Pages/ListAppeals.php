<?php

namespace App\Filament\Resources\AppealResource\Pages;

use App\Filament\Resources\AppealResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppeals extends ListRecords
{
    protected static string $resource = AppealResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
