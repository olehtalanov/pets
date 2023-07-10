<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CoordinatesTest extends TestCase
{
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_user_can_read_his_coordinates(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->getJson('/api/v1/users/coordinates');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'latitude',
                'longitude',
            ]);
    }

    public function test_user_can_store_his_coordinates(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('/api/v1/users/coordinates', [
            'latitude' => $latitude = $this->faker->latitude,
            'longitude' => $longitude = $this->faker->longitude,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->where('latitude', $latitude)
                    ->where('longitude', $longitude)
            );
    }

}
