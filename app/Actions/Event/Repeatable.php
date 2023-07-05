<?php

namespace App\Actions\Event;

use App\Enums\EventRepeatSchemeEnum;
use App\Models\Event;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Collection;

abstract class Repeatable
{
    protected int $diffInMinutes = 15;

    protected Carbon $endDate;

    protected Collection $categories;

    public function __construct(
        protected Event   $event,
        protected ?Carbon $fromDate = null
    ) {
        $this->diffInMinutes = $this->event->starts_at->diffInMinutes($this->event->ends_at);

        $this->endDate = match ($this->event->repeat_scheme) {
            EventRepeatSchemeEnum::Never => $this->event->ends_at,
            EventRepeatSchemeEnum::EveryDay => $this->event->starts_at->addDays(
                config('app.events.repeat.days')
            ),
            EventRepeatSchemeEnum::EveryWorkingDay => $this->event->starts_at->addDays(
                config('app.events.repeat.working_days')
            ),
            EventRepeatSchemeEnum::EveryWeekend,
            EventRepeatSchemeEnum::EveryWeek => $this->event->starts_at->addDays(
                config('app.events.repeat.weekends')
            ),
            EventRepeatSchemeEnum::EveryMonth => $this->event->starts_at->addMonths(
                config('app.events.repeat.months')
            ),
            EventRepeatSchemeEnum::EveryYear => $this->event->starts_at->addYears(
                config('app.events.repeat.months')
            ),
        };

        $this->categories = $this->event->categories()->pluck('id');

        if (!$this->event->wasRecentlyCreated
            && $this->event->processable
            && $this->event->isDirty('repeat_scheme')) {
            $this->event->children()->delete();
        }
    }

    protected function handle(): Closure
    {
        return function (Carbon $date) {
            $event = new Event($this->event->toArray());

            $event->starts_at = $date;
            $event->ends_at = $date->addMinutes($this->diffInMinutes);
            $event->original_id = $this->event->getKey();

            $event->saveQuietly();
            $event->categories()->attach($this->categories);
        };
    }

    abstract public function create(): void;
}
