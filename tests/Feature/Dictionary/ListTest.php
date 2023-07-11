<?php

namespace Tests\Feature\Dictionary;

use App\Models\User;
use Tests\TestCase;

class ListTest extends TestCase
{
    public function test_user_cant_list_dictionaries_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/dictionaries');

        $response->assertStatus(401);
    }

    public function test_dictionary_list(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->getJson('/api/v1/dictionaries');

        $response
            ->assertOk()
            ->assertJsonIsObject()
            ->assertJsonStructure([
                'types' => [
                    'animals',
                    'pins',
                ],
                'categories' => [
                    'events',
                    'notes',
                    'common',
                ],
                'repeatable' => [
                    'events',
                ],
            ]);
    }
}
