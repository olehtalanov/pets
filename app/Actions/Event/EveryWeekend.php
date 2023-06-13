<?php

namespace App\Actions\Event;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EveryWeekend extends Repeatable
{
    public function create(): void
    {
        CarbonPeriod::create(
            $this->event->starts_at->addDay(),
            $this->endDate
        )
            ->addFilter(fn (Carbon $date) => $date->isWeekend())
            ->forEach($this->handle());
    }
}
