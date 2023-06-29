<?php

namespace App\Data\Chat;

use App\Models\User;
use Auth;
use Spatie\LaravelData\Data;

class MessageData extends Data
{
    public function __construct(
        public string $message,
        public string $recipient_id,
        public ?int   $user_id
    ) {
        $this->user_id = Auth::id();

        if ($this->recipient_id) {
            $this->recipient_id = User::whereUuid($this->recipient_id)->firstOrFail()->getKey();
        }
    }
}
