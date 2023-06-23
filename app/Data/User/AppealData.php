<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;

class AppealData extends Data
{
    public string $message;

    public ?int $rating = null;
}
