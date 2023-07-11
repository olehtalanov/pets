<?php

namespace Tests\Feature\Pin;

use App\Models\Pin;
use App\Models\PinType;
use App\Models\User;
use Illuminate\Support\Fluent;
use Illuminate\Testing\Fluent\AssertableJson;

class PinsTest extends BaseTestCase
{
    public function test_user_cant_interact_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/pins');
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/pins');
        $response->assertStatus(401);
    }


    public function test_user_can_view_own_pins(): void
    {
        $this->actingAs($this->user, 'sanctum');

        Pin::factory(50)->create();

        Pin::factory(10)->create([
            'user_id' => $this->user
        ]);

        $response = $this->getJson('/api/v1/pins');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'items')
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

    public function test_user_can_view_pin(): void
    {
        $decoded = $this->createPin()->decodeResponseJson();

        $this->actingAs($this->user, 'sanctum');

        $response = $this->getJson('/api/v1/pins/' . $decoded['uuid']);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'uuid',
                'name',
                'type',
                'latitude',
                'longitude',
                'rating',
                'own_review_exists',
                'description',
                'address',
                'gallery' => [
                    'uuid',
                    'url',
                ],
            ]);
    }

    public function test_user_can_create_pin(): void
    {
        $this->createPin();
    }

    public function test_user_can_update_pin(): void
    {
        $decoded = $this->createPin()->decodeResponseJson();

        $this->actingAs($this->user, 'sanctum');

        $type = PinType::factory()->create();

        $attributes = new Fluent([
            'name' => $this->faker->sentence,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'type_id' => $type->uuid,
            'description' => null,
            'address' => $this->faker->address,
            'contact' => null,
        ]);

        $response = $this->patchJson('/api/v1/pins/' . $decoded['uuid'], $attributes->toArray());

        $response
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('name', $attributes->get('name'))
                ->where('latitude', $attributes->get('latitude'))
                ->where('longitude', $attributes->get('longitude'))
                ->where('description', $attributes->get('description'))
                ->where('address', $attributes->get('address'))
                ->where('contact', $attributes->get('contact'))
                ->where('type', $type->name)
                ->etc()
            );
    }

    public function test_another_user_cant_update_pin(): void
    {
        $decoded = $this->createPin()->decodeResponseJson();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $type = PinType::factory()->create();

        $attributes = new Fluent([
            'name' => $this->faker->sentence,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'type_id' => $type->uuid,
            'description' => null,
            'address' => $this->faker->address,
            'contact' => null,
        ]);

        $response = $this->patchJson('/api/v1/pins/' . $decoded['uuid'], $attributes->toArray());

        $response->assertStatus(403);
    }

    public function test_user_can_delete_pin(): void
    {
        $decoded = $this->createPin()->decodeResponseJson();

        $this->actingAs($this->user, 'sanctum');

        $response = $this->deleteJson('/api/v1/pins/' . $decoded['uuid']);

        $response->assertNoContent();
    }

    public function test_another_user_cant_delete_pin(): void
    {
        $decoded = $this->createPin()->decodeResponseJson();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->deleteJson('/api/v1/pins/' . $decoded['uuid']);

        $response->assertStatus(403);
    }
}
