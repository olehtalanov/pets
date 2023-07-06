<?php

namespace Tests\Feature\Auth;

use App\Models\PersonalAccessCode;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_user_can_auth_with_email_and_receive_a_code(): void
    {
        $response = $this->postJson('/api/v1/auth/code', [
            'email' => 'test@mail.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@mail.com',
        ]);

        $response->assertStatus(204);
    }

    public function test_user_cant_login_with_wrong_code(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@mail.com',
            'code' => "000000",
            'device_name' => 'Test'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'code',
            ]);
    }

    public function test_user_can_login_with_correct_code(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@mail.com',
            'code' => PersonalAccessCode::query()
                ->where('user_id', 1)
                ->latest('id')
                ->first()
                ?->code,
            'device_name' => 'Test'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'profile' => [
                    'uuid',
                    'name',
                    'first_name',
                    'last_name',
                    'email',
                    'avatar' => [
                        'thumb',
                        'full',
                    ]
                ],
            ]);
    }
}
