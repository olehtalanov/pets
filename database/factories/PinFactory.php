<?php

namespace Database\Factories;

use App\Models\Pin;
use App\Models\PinType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pin>
 */
class PinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'description' => implode("\n", $this->faker->sentences),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'address' => $this->faker->address,
            'contact' => $this->faker->email,
            'user_id' => User::factory()->create(),
            'type_id' => PinType::factory()->create()
        ];
    }
}
