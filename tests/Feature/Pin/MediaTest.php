<?php

namespace Tests\Feature\Pin;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Storage;

class MediaTest extends BaseTestCase
{
    public function test_user_can_upload_images(): void
    {
        $this->uploadImages();

        Storage::fake('public');
    }

    public function test_user_cant_upload_non_images(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->createPin()->decodeResponseJson();

        $response = $this->postJson('/api/v1/pins/' . $decoded['uuid'] . '/media', [
            'files' => [
                UploadedFile::fake()->create('wrong.pdf', 200, 'application/pdf'),
            ]
        ]);

        $response->assertJsonValidationErrors('files.0');
    }

    public function test_user_can_delete_image(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->uploadImages()->decodeResponseJson();

        $response = $this->getJson('/api/v1/pins/' . $decoded['uuid']);
        $response
            ->assertOk()
            ->assertJsonCount(2, 'gallery');

        $response = $this->deleteJson('/api/v1/pins/' . $decoded['uuid'] . '/media/' . $response->decodeResponseJson()['gallery'][0]['uuid']);
        $response->assertNoContent();

        $response = $this->getJson('/api/v1/pins/' . $decoded['uuid']);
        $response
            ->assertOk()
            ->assertJsonCount(1, 'gallery');
    }

    public function test_another_user_cant_delete_image(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->uploadImages()->decodeResponseJson();

        $response = $this->getJson('/api/v1/pins/' . $decoded['uuid']);
        $response
            ->assertOk()
            ->assertJsonCount(2, 'gallery');

        $this->actingAs(User::factory()->create());

        $response = $this->deleteJson('/api/v1/pins/' . $decoded['uuid'] . '/media/' . $response->decodeResponseJson()['gallery'][0]['uuid']);
        $response->assertStatus(403);

        $response = $this->getJson('/api/v1/pins/' . $decoded['uuid']);
        $response
            ->assertOk()
            ->assertJsonCount(2, 'gallery');
    }

    private function uploadImages(): TestResponse
    {
        $this->actingAs($this->user, 'sanctum');

        $created = $this->createPin();

        $decoded = $created->decodeResponseJson();

        Storage::fake('public');

        $response = $this->postJson('/api/v1/pins/' . $decoded['uuid'] . '/media', [
            'files' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ]
        ]);

        $response->assertOk();

        return $created;
    }


}
