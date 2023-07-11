<?php

namespace Tests\Feature\Pin;

use App\Models\Pin;
use App\Models\PinType;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_user_can_search(): void
    {
        $this->actingAs($this->user, 'sanctum');

        Pin::factory(5)->create();

        $response = $this->getJson('/api/v1/pins/search');

        $response
            ->assertOk()
            ->assertJsonCount(5, 'items')
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'uuid',
                        'name',
                        'type',
                        'latitude',
                        'longitude',
                        'rating',
                        'own_review_exists',
                    ]
                ],
                'meta' => [
                    'total',
                    'current',
                    'nextLink'
                ],
            ]);
    }

    public function test_user_can_search_with_params(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $coordinates = [
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];

        Pin::factory(50)->create()->each(fn(Pin $pin) => $pin->updateQuietly([
            'latitude' => $coordinates['latitude'] + (random_int(-100, 100) / random_int(100, 5000)),
            'longitude' => $coordinates['longitude'] + (random_int(-100, 100) / random_int(100, 5000)),
        ]));

        $items = Pin::radius(
            $coordinates['latitude'],
            $coordinates['longitude'],
            1500 / 1000 // in meters
        )->count();

        $response = $this->getJson('/api/v1/pins/search?' . http_build_query(
                array_merge($coordinates, [
                    'radius' => 1500
                ])
            ));

        $response
            ->assertOk()
            ->assertJsonCount($items, 'items')
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'uuid',
                        'name',
                        'type',
                        'latitude',
                        'longitude',
                        'rating',
                        'own_review_exists',
                    ]
                ],
                'meta' => [
                    'total',
                    'current',
                    'nextLink'
                ],
            ]);
    }

    public function test_user_can_search_by_type(): void
    {
        $this->actingAs($this->user, 'sanctum');

        Pin::factory(10)->create();

        $types = PinType::inRandomOrder()->first();

        $response = $this->getJson('/api/v1/pins/search?' . http_build_query([
                'type_ids' => $types->pluck('uuid')->toArray()
            ]));

        $response
            ->assertOk()
            ->assertJsonPath('items.0.type', fn(string $type) => $types->pluck('name')->contains($type));
    }

    public function test_user_cant_search_with_wrong_params(): void
    {
        $this->actingAs($this->user, 'sanctum');

        Pin::factory(50)->create();

        $response = $this->getJson('/api/v1/pins/search?' . http_build_query([
                'latitude' => $this->faker->latitude,
                'longitude' => $this->faker->longitude,
            ]));

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('radius');

        $response = $this->getJson('/api/v1/pins/search?' . http_build_query([
                'radius' => 500,
                'longitude' => $this->faker->longitude,
            ]));

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('latitude');

        $response = $this->getJson('/api/v1/pins/search?' . http_build_query([
                'radius' => 500,
                'latitude' => $this->faker->latitude,
            ]));

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('longitude');
    }
}
