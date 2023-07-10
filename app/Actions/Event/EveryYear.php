<?php

namespace App\Actions\Event;

use Carbon\CarbonPeriod;

class EveryYear extends Repeatable
{
    public function create(): void
    {
        CarbonPeriod::create(
            $this->event->starts_at->addYear(),
            '1 year',
            $this->endDate
        )->forEach($this->handle());
    }
}
