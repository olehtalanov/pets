<?php

namespace App\Actions\Event;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class EveryWorkingDay extends Repeatable
{
    public function create(): void
    {
        if (!now()->isWeekend()) {
            $period = CarbonPeriod::create(
                $this->event->starts_at->addDay(),
                $this->endDate
            );
        } else {
            $period = CarbonPeriod::create(
                $this->event->starts_at->next(CarbonInterface::TUESDAY),
                $this->endDate
            );

            $next = $this->event->starts_at->next(CarbonInterface::MONDAY);

            $this->event->updateQuietly([
                'starts_at' => $next,
                'ends_at' => $this->event->ends_at->addMinutes(
                    $this->event->starts_at->diffInMinutes($next)
                ),
            ]);
        }

        $period
            ->addFilter(fn(Carbon $date) => !$date->isWeekend())
            ->forEach($this->handle());
    }
}
