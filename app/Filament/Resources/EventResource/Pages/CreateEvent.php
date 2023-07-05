<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Jobs\Event\RepeatEvent;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function afterCreate(): void
    {
        dispatch(new RepeatEvent($this->record));
    }
}
