<?php

namespace App\Data\Animal;

use App\Models\AnimalType;
use App\Models\Breed;
use Spatie\LaravelData\Data;

class AnimalData extends Data
{
    public function __construct(
        public string  $name,
        public string  $sex,
        public string  $birth_date,
        public string  $breed_name,
        public bool    $metis,
        public bool    $sterilised,
        public float   $weight,
        public string  $weight_unit,
        public ?string $custom_type_name,
        public ?string $custom_breed_name,
        public ?string $animal_type_id,
        public ?string $breed_id,
    )
    {
        if ($animal_type_id !== null) {
            $this->animal_type_id = AnimalType::findUOrFail($animal_type_id)->getKey();
            $this->custom_type_name = null;
        }

        if ($breed_id !== null) {
            $this->breed_id = Breed::findUOrFail($breed_id)->getKey();
            $this->custom_breed_name = null;
        }
    }
}
