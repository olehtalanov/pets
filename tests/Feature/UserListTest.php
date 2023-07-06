<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserListTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first();
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
            ->assertStatus(200)
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
}
