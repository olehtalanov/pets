<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first();
    }

    public function test_cant_view_profile_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/profile');

        $response->assertStatus(401);
    }

    public function test_user_has_access_to_his_profile(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->getJson('/api/v1/profile');

        $response
            ->assertStatus(200)
            ->assertJsonIsObject()
            ->assertJsonStructure([
                'uuid',
                'name',
                'first_name',
                'last_name',
                'email',
                'avatar' => [
                    'thumb',
                    'full',
                ]
            ]);
    }

    public function test_user_can_update_his_profile(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $response = $this->patchJson('/api/v1/profile', [
            'email' => $this->user->email,
            'first_name' => $firstName = $this->faker->firstName,
            'last_name' => $lastName = $this->faker->lastName,
            'phone' => $phone = $this->faker->e164PhoneNumber
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonIsObject()
            ->assertJsonStructure([
                'uuid',
                'name',
                'first_name',
                'last_name',
                'email',
                'avatar' => [
                    'thumb',
                    'full',
                ]
            ])
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('uuid', $this->user->uuid)
                ->where('first_name', $firstName)
                ->where('last_name', $lastName)
                ->etc()
            )
            ->assertJsonMissing([
                'phone'
            ]);

        $this->assertDatabaseHas('users', [
            'phone' => substr($phone, 1), // remove + added by mutator
        ]);
    }

    public function test_user_can_upload_avatar(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson('/api/v1/profile/avatar', [
            'avatar' => $file,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'thumb',
                'full',
            ]);
    }
}