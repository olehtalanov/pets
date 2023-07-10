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
        $type = AnimalType::inRandomOrder()->first() ?? AnimalType::factory()->create();

        return [
            'name' => $this->faker->word,
            'animal_type_id' => $type->getKey(),
        ];
    }
}
