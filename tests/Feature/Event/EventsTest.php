<?php

namespace Tests\Feature\Event;

use App\Enums\EventRepeatSchemeEnum;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EventsTest extends TestCase
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

    public function test_user_cant_interact_events_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/events');

        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/events');

        $response->assertStatus(401);
    }

    public function test_user_can_view_all_events(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'items',
                'meta',
            ]);
    }

    public function test_user_can_create_event(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $categories = Category::onlyChildren(Event::class)->take(random_int(1, 5))->pluck('uuid');

        $response = $this->postJson('/api/v1/events', [
            'title' => $title = $this->faker->sentence,
            'repeat_scheme' => EventRepeatSchemeEnum::Never->value,
            'whole_day' => false,
            'category_ids' => $categories,
            'animal_id' => $animal->uuid,
            'starts_at' => now()->addDay()->toDateTimeString(),
            'ends_at' => now()->addDays(3)->toDateTimeString(),
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('title', $title)
                ->where('repeat.scheme', EventRepeatSchemeEnum::Never->value)
                ->where('whole_day', false)
                ->where('starts_at', now()->addDay()->toDateTimeString())
                ->where('ends_at', now()->addDays(3)->toDateTimeString())
                ->where('animal.uuid', $animal->uuid)
                ->etc()
            )
            ->assertJsonStructure([
                'uuid',
                'title',
                'description',
                'starts_at',
                'ends_at',
                'repeat' => [
                    'scheme',
                    'name',
                ],
                'whole_day',
                'animal' => [
                    'uuid',
                    'name',
                    'birth_date',
                    'type',
                    'breed',
                    'sex',
                    'weight',
                    'avatar' => [
                        'thumb',
                        'full',
                    ],
                    'activity' => [
                        'notes',
                        'events',
                    ]
                ],
                'categories' => [
                    '*' => [
                        'uuid',
                        'name',
                    ]
                ]
            ]);
    }

    public function test_whole_day_option(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $categories = Category::onlyChildren(Event::class)->take(random_int(1, 5))->pluck('uuid');

        $response = $this->postJson('/api/v1/events', [
            'title' => $this->faker->sentence,
            'repeat_scheme' => EventRepeatSchemeEnum::Never->value,
            'whole_day' => true,
            'category_ids' => $categories,
            'animal_id' => $animal->uuid,
            'starts_at' => now()->addDay()->toDateTimeString(),
            'ends_at' => now()->addDays(3)->toDateTimeString(),
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('whole_day', true)
                ->where('starts_at', now()
                    ->addDay()
                    ->startOfDay()
                    ->toDateTimeString()
                )
                ->where('ends_at', now()
                    ->addDays(3)
                    ->endOfDay()
                    ->toDateTimeString()
                )
                ->etc()
            );
    }
}
