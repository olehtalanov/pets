<?php

namespace Tests\Feature\Event;

use App\Enums\EventRepeatSchemeEnum;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

class EventsTest extends BaseTestCase
{
    protected array $fullStructure = [
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
    ];

    public function test_user_cant_interact_events_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/events');

        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/events');

        $response->assertStatus(401);
    }

    public function test_user_can_view_own_events(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvent(EventRepeatSchemeEnum::EveryMonth);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'uuid',
                        'title',
                        'starts_at',
                        'ends_at',
                        'repeat' => [
                            'name',
                            'scheme',
                        ],
                        'whole_day',
                        'animal',
                        'categories',
                    ]
                ],
                'meta',
            ]);
    }

    public function test_user_can_view_single_event(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->createEvent(EventRepeatSchemeEnum::Never)->decodeResponseJson();

        $response = $this->getJson('/api/v1/events/' . $decoded['uuid']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure($this->fullStructure);
    }

    public function test_user_can_create_event(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $categories = Category::onlyChildren(Event::class)->take(random_int(1, 5))->pluck('uuid');

        $response = $this->postJson('/api/v1/events', [
            'repeat_scheme' => EventRepeatSchemeEnum::Never->value,
            'title' => $this->faker->sentence,
            'whole_day' => false,
            'category_ids' => $categories,
            'animal_id' => $animal->uuid,
            'starts_at' => $start = now()->addDay()->toDateTimeString(),
            'ends_at' => $end = now()->addDays(3)->toDateTimeString(),
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('repeat.scheme', EventRepeatSchemeEnum::Never->value)
                ->where('whole_day', false)
                ->where('starts_at', $start)
                ->where('ends_at', $end)
                ->where('animal.uuid', $animal->uuid)
                ->has('categories', $categories->count())
                ->has('animal')
                ->etc()
            )
            ->assertJsonStructure($this->fullStructure);
    }

    public function test_user_can_create_event_without_categories(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $response = $this->postJson('/api/v1/events', [
            'repeat_scheme' => EventRepeatSchemeEnum::Never->value,
            'title' => $this->faker->sentence,
            'whole_day' => false,
            'animal_id' => $animal->uuid,
            'starts_at' => $start = now()->addDay()->toDateTimeString(),
            'ends_at' => $end = now()->addDays(3)->toDateTimeString(),
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('repeat.scheme', EventRepeatSchemeEnum::Never->value)
                ->where('whole_day', false)
                ->where('starts_at', $start)
                ->where('ends_at', $end)
                ->where('animal.uuid', $animal->uuid)
                ->has('categories', 0)
                ->has('animal')
                ->etc()
            )
            ->assertJsonStructure($this->fullStructure);
    }

    public function test_user_can_update_event(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $categories = Category::onlyChildren(Event::class)->take(random_int(1, 5))->pluck('uuid');

        $decoded = $this->createEvent(EventRepeatSchemeEnum::Never)->decodeResponseJson();

        $response = $this->patchJson('/api/v1/events/' . $decoded['uuid'], [
            'repeat_scheme' => EventRepeatSchemeEnum::Never->value,
            'title' => $title = $this->faker->sentence,
            'category_ids' => $categories,
            'starts_at' => now()->addDay()->toDateTimeString(),
            'ends_at' => now()->addDays(3)->toDateTimeString(),
            'animal_id' => $animal->uuid,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('title', $title)
                ->where('starts_at', now()->addDay()->toDateTimeString())
                ->where('ends_at', now()->addDays(3)->toDateTimeString())
                ->where('animal.uuid', $animal->uuid)
                ->has('categories', $categories->count())
                ->has('animal')
                ->etc()
            )
            ->assertJsonStructure($this->fullStructure);
    }

    public function test_another_user_cant_update_event(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $decoded = $this->createEvent(EventRepeatSchemeEnum::Never)->decodeResponseJson();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->patchJson('/api/v1/events/' . $decoded['uuid'], [
            'repeat_scheme' => EventRepeatSchemeEnum::Never->value,
            'title' => $this->faker->sentence,
            'starts_at' => now()->addDay()->toDateTimeString(),
            'ends_at' => now()->addDays(3)->toDateTimeString(),
            'animal_id' => $animal->uuid,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_create_event_with_whole_day_option(): void
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

    public function test_user_can_delete_event(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->createEvent(EventRepeatSchemeEnum::Never)->decodeResponseJson();

        $response = $this->deleteJson('/api/v1/events/' . $decoded['uuid']);

        $response->assertStatus(204);
    }

    public function test_another_user_cant_delete_event(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->createEvent(EventRepeatSchemeEnum::Never)->decodeResponseJson();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->deleteJson('/api/v1/events/' . $decoded['uuid']);

        $response->assertStatus(403);
    }
}
