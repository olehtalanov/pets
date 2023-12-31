<?php

namespace App\Filament\Resources\AppealResource\Pages;

use App\Filament\Resources\AppealResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppeal extends EditRecord
{
    protected static string $resource = AppealResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
