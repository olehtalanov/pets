<?php

namespace Tests\Feature\Pin;

use App\Models\PinType;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Fluent;
use Illuminate\Testing\Fluent\AssertableJson;
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
    }

    protected function createPin(): TestResponse
    {
        $this->actingAs($this->user, 'sanctum');

        $type = PinType::factory()->create();

        $attributes = new Fluent([
            'name' => $this->faker->sentence,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'type_id' => $type->uuid,
            'description' => implode("\n", $this->faker->sentences),
            'address' => $this->faker->address,
            'contact' => $this->faker->email,
        ]);

        $response = $this->postJson('/api/v1/pins', $attributes->toArray());

        $response
            ->assertCreated()
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

        return $response;
    }
}
