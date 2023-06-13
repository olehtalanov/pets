<?php

namespace App\Actions\Event;

use App\Models\Event;
use Carbon\Carbon;
use Closure;

abstract class Repeatable
{
    protected int $diffInMinutes = 15;

    protected Carbon $endDate;

    public function __construct(
        protected Event $event,
        protected ?Carbon $fromDate = null
    ) {
        $this->diffInMinutes = $this->event->starts_at->diffInMinutes($this->event->ends_at);
        $this->endDate = $this->event->starts_at->addMonths(config('app.events.future_repeatable'));

        if (! $this->event->wasRecentlyCreated && $this->event->isDirty('repeat_scheme')) {
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
        };
    }

    abstract public function create(): void;
}
