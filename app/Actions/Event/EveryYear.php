<?php

namespace App\Actions\Event;

use App\Models\Event;

class EveryYear extends Repeatable
{
    public function create(): void
    {
        $event = new Event($this->event->toArray());

        $event->starts_at = $this->event->starts_at->addYear();
        $event->ends_at = $this->event->ends_at->addYear();
        $event->original_id = $this->event->getKey();

        $event->saveQuietly();
    }
}
