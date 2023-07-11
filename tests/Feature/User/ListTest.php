<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class ListTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_get_list_without_authorization(): void
    {
        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(401);
    }

    public function test_can_get_list_of_users(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->getJson('/api/v1/users');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'uuid',
                        'name',
                        'avatar',
                        'latitude',
                        'longitude',
                        'animals' => [
                            '*' => [
                                'uuid',
                                'name',
                                'type',
                                'breed',
                                'sex',
                                'avatar',
                            ]
                        ]
                    ]
                ],
                'meta' => [
                    'total',
                    'current',
                    'nextLink',
                ]
            ]);
    }

    public function test_user_can_search_by_coordinates(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->getJson('/api/v1/users?' . http_build_query([
                'latitude' => $this->user->latitude,
                'longitude' => $this->user->longitude,
                'radius' => 500
            ]));

        $response
            ->assertOk()
            ->assertJsonPath('items.0.uuid', $this->user->uuid);
    }

    public function test_user_provide_all_required_for_search_params(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this
            ->getJson('/api/v1/users?' . http_build_query([
                    'latitude' => $this->user->latitude,
                ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'longitude',
                'radius',
            ]);

        $this
            ->getJson('/api/v1/users?' . http_build_query([
                    'longitude' => $this->user->longitude,
                ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'latitude',
                'radius',
            ]);

        $this
            ->getJson('/api/v1/users?' . http_build_query([
                    'latitude' => $this->user->latitude,
                    'longitude' => $this->user->longitude,
                ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'radius',
            ]);

        $this
            ->getJson('/api/v1/users?' . http_build_query([
                    'latitude' => $this->user->latitude,
                    'radius' => 500,
                ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'longitude',
            ]);

        $this
            ->getJson('/api/v1/users?' . http_build_query([
                    'longitude' => $this->user->longitude,
                    'radius' => 500,
                ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'latitude',
            ]);
    }
}
