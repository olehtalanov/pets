<?php

namespace App\Data\User;

use Auth;
use Spatie\LaravelData\Data;

class ReviewData extends Data
{
    public int $user_id;

    public function __construct(
        public int     $rating,
        public ?string $message = null
    )
    {
        $this->user_id = Auth::id();
    }
}
