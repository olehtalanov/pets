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

class RepeatSchemeTest extends TestCase
{
    use WithFaker;

    private User $user;

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

    public function test_never_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvents(EventRepeatSchemeEnum::Never);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'items');
    }

    public function test_every_day_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvents(EventRepeatSchemeEnum::EveryDay);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.days'), 'items');
    }

    public function test_every_working_day_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvents(EventRepeatSchemeEnum::EveryWorkingDay);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.weeks') * 5, 'items');
    }

    public function test_every_weekend_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvents(EventRepeatSchemeEnum::EveryWeekend);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.weeks') * 2, 'items');
    }

    public function test_every_week_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvents(EventRepeatSchemeEnum::EveryWeek);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.weeks') + 1, 'items');
    }

    public function test_every_month_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvents(EventRepeatSchemeEnum::EveryMonth);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.months') + 1, 'items');
    }

    public function test_every_year_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvents(EventRepeatSchemeEnum::EveryYear);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.years') + 1, 'items');
    }

    private function createEvents(EventRepeatSchemeEnum $scheme): TestResponse
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
