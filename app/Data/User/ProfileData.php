<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;

class ProfileData extends Data
{
    public string $first_name;

    public string $last_name;

    public string $email;

    public ?string $phone;
}
