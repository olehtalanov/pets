<?php

namespace App\Actions\Event;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EveryWorkingDay extends Repeatable
{
    public function create(): void
    {
        CarbonPeriod::create(
            $this->event->starts_at->addYear(),
            '1 year',
            $this->endDate
        )
            ->addFilter(fn (Carbon $date) => ! $date->isWeekend())
            ->forEach($this->handle());
    }
}
