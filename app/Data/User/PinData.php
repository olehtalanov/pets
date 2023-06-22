<?php

namespace App\Data\User;

use App\Models\AnimalType;
use Spatie\LaravelData\Data;

class PinData extends Data
{
    public function __construct(
        public string  $name,
        public float   $latitude,
        public float   $longitude,
        public string  $type_id,
        public ?string $address = null,
        public ?string $contact = null,
    )
    {
        $this->type_id = AnimalType::findU($type_id)->getKey();
    }
}
