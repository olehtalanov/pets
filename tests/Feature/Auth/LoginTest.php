<?php

namespace Tests\Feature\Auth;

use App\Models\PersonalAccessCode;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private User $user;

    private PersonalAccessCode $code;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->code = $this->user->accessCode()->create();
    }

    public function test_user_can_auth_with_email_and_receive_a_code(): void
    {
        $response = $this->postJson('/api/v1/auth/code', [
            'email' => $this->user->email,
        ]);

        $response
            ->assertStatus(204)
            ->assertNoContent();
    }

    public function test_user_cant_login_with_wrong_code(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
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
            'email' => $this->user->email,
            'code' => $this->user->accessCode->code,
            'device_name' => 'Test'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'profile' => [
                    'uuid',
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'avatar' => [
                        'thumb',
                        'full',
                    ]
                ],
            ])
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('profile.email', $this->user->email)
                ->where('profile.first_name', $this->user->first_name)
                ->where('profile.last_name', $this->user->last_name)
                ->etc()
            );
    }
}
