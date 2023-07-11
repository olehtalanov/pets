<?php

namespace Tests\Feature\Review;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Fluent;
use Storage;
use Tests\Feature\Pin\BaseTestCase;

class ReviewsMediaTest extends BaseTestCase
{
    public function test_user_can_upload_images(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $pinID = $this->createPin()->decodeResponseJson()['uuid'];

        $attributes = new Fluent([
            'rating' => random_int(1, 5),
            'message' => $this->faker->sentence
        ]);

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews', $attributes->toArray());
        $response->assertCreated();

        Storage::fake('public');

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews/' . $response->decodeResponseJson()['uuid'] . '/media', [
            'files' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ]
        ]);

        $response->assertOk();

        Storage::fake('public');
    }

    public function test_user_can_delete_image(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $pinID = $this->createPin()->decodeResponseJson()['uuid'];

        $attributes = new Fluent([
            'rating' => random_int(1, 5),
            'message' => $this->faker->sentence
        ]);

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews', $attributes->toArray());
        $response->assertCreated();

        $review = $response->decodeResponseJson();

        Storage::fake('public');

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews/' . $review['uuid'] . '/media', [
            'files' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ]
        ]);
        $response->assertOk();

        $response = $this->getJson('/api/v1/pins/' . $pinID . '/reviews/' . $review['uuid']);
        $response
            ->assertOk()
            ->assertJsonCount(2, 'gallery');

        $review = $response->decodeResponseJson();

        $response = $this->deleteJson('/api/v1/pins/' . $pinID . '/reviews/' . $review['uuid'] . '/media/' . $review['gallery'][0]['uuid']);
        $response->assertNoContent();

        $response = $this->getJson('/api/v1/pins/' . $pinID . '/reviews/' . $review['uuid']);
        $response
            ->assertOk()
            ->assertJsonCount(1, 'gallery');

        Storage::fake('public');
    }

    public function test_user_cant_delete_foreign_image(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $pinID = $this->createPin()->decodeResponseJson()['uuid'];

        $attributes = new Fluent([
            'rating' => random_int(1, 5),
            'message' => $this->faker->sentence
        ]);

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews', $attributes->toArray());
        $response->assertCreated();

        $review = $response->decodeResponseJson();

        Storage::fake('public');

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews/' . $review['uuid'] . '/media', [
            'files' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ]
        ]);
        $response->assertOk();

        $response = $this->getJson('/api/v1/pins/' . $pinID . '/reviews/' . $review['uuid']);
        $response
            ->assertOk()
            ->assertJsonCount(2, 'gallery');

        $review = $response->decodeResponseJson();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->deleteJson('/api/v1/pins/' . $pinID . '/reviews/' . $review['uuid'] . '/media/' . $review['gallery'][0]['uuid']);
        $response->assertStatus(403);

        Storage::fake('public');
    }
}
