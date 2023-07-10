<?php

namespace Database\Factories;

use App\Enums\EventRepeatSchemeEnum;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'repeat_scheme' => EventRepeatSchemeEnum::Never,
            'whole_day' => false,
        ];
    }
}
