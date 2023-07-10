<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class AppealTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_user_cant_leave_feedback_without_authentication(): void
    {
        $response = $this->postJson('/api/v1/appeals', [
            'message' => 'test message',
            'rating' => 5,
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_leave_feedback(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('/api/v1/appeals', [
            'message' => 'test message',
            'rating' => 5,
        ]);

        $response->assertStatus(204);
    }
}
