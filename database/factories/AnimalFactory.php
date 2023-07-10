<?php

namespace Database\Factories;

use App\Enums\SexEnum;
use App\Enums\WeightUnitEnum;
use App\Models\Animal;
use App\Models\Breed;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * @throws Exception
     */
    public function definition(): array
    {
        /** @var Breed $breed */
        $breed = Breed::inRandomOrder()->first() ?? Breed::factory()->create();

        return [
            "name" => $this->faker->word,
            "sex" => random_int(0, 1) ? SexEnum::Male : SexEnum::Female,
            "birth_date" => $this->faker->date,
            "metis" => (bool)random_int(0, 1),
            "weight" => random_int(1, 50),
            "weight_unit" => WeightUnitEnum::Kg,
            "breed_name" => $this->faker->word,
            "breed_id" => $breed->getKey(),
            "animal_type_id" => $breed->type->getKey(),
        ];
    }
}
