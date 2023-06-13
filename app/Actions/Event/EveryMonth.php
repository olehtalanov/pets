<?php

namespace App\Actions\Event;

use Carbon\CarbonPeriod;

class EveryMonth extends Repeatable
{
    public function create(): void
    {
        CarbonPeriod::create(
            $this->event->starts_at->addMonth(),
            '1 month',
            $this->endDate
        )->forEach($this->handle());
    }
}
