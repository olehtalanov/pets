<?php

namespace App\Actions\Event;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class EveryWeekend extends Repeatable
{
    public function create(): void
    {
        CarbonPeriod::create(
            $this->event->starts_at->next(CarbonInterface::SUNDAY),
            $this->endDate
        )
            ->addFilter(fn(Carbon $date) => $date->isWeekend())
            ->forEach($this->handle());

        if (!$this->event->starts_at->isWeekend()) {
            $next = $this->event->starts_at->next(CarbonInterface::SATURDAY);

            $this->event->updateQuietly([
                'starts_at' => $next,
                'ends_at' => $this->event->ends_at->addMinutes(
                    $this->event->starts_at->diffInMinutes($next)
                ),
            ]);
        }
    }
}
