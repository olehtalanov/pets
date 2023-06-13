<?php

namespace App\Actions\Event;

use Carbon\CarbonPeriod;

class EveryDay extends Repeatable
{
    public function create(): void
    {
        CarbonPeriod::create(
            $this->event->starts_at->addDay(),
            $this->endDate
        )->forEach($this->handle());
    }
}
