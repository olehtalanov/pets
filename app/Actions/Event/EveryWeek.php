<?php

namespace App\Actions\Event;

use Carbon\CarbonPeriod;

class EveryWeek extends Repeatable
{
    public function create(): void
    {
        CarbonPeriod::create(
            $this->event->starts_at->addWeek(),
            '1 week',
            $this->endDate
        )->forEach($this->handle());
    }
}
