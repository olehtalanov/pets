<?php

namespace Tests\Feature\Event;

use App\Enums\EventRepeatSchemeEnum;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $parentCategory = Category::factory()->create([
            'related_model' => Event::class,
        ]);

        Category::factory(10)->create([
            'related_model' => Event::class,
            'parent_id' => $parentCategory->getKey()
        ]);
    }

    protected function createEvent(EventRepeatSchemeEnum $scheme): TestResponse
    {
        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $categories = Category::onlyChildren(Event::class)->take(random_int(1, 5))->pluck('uuid');

        return $this->postJson('/api/v1/events', [
            'title' => $this->faker->sentence,
            'repeat_scheme' => $scheme->value,
            'whole_day' => false,
            'category_ids' => $categories,
            'animal_id' => $animal->uuid,
            'starts_at' => now(),
            'ends_at' => now()->addHour(),
        ]);
    }
}
