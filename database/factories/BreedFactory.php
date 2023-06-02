<?php

namespace Database\Factories;

use App\Models\AnimalType;
use App\Models\Breed;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Breed>
 */
class BreedFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'animal_type_id' => AnimalType::inRandomOrder()->firstOrFail()?->id,
        ];
    }
}
