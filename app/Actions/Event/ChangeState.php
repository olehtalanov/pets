<?php

namespace App\Actions\Event;

use App\Models\Event;

class ChangeState
{
    public array $additional = [];

    public bool $generateChildren = false;

    public function __construct(
        private readonly Event $event,
        private readonly bool $processable
    ) {
        //
    }

    public static function make(
        Event $event,
        bool $processable
    ): static {
        return new static($event, $processable);
    }

    public function handle(): void
    {
        if ($this->processable) {
            $this->additional['original_id'] = null;
            $this->additional['processable'] = 1;

            if ($this->event->original_id) {
                Event::whereId($this->event->original_id)->updateQuietly([
                    'processable' => 0,
                ]);

                Event::where('original_id', $this->event->original_id)
                    ->where('id', '>', $this->event->id)
                    ->deleteQuietly();

                $this->generateChildren = true;
            }
        }
    }
}
