<?php

namespace Tests\Feature\Review;

use App\Models\User;
use Illuminate\Support\Fluent;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Pin\BaseTestCase;

class ReviewsTest extends BaseTestCase
{
    public function test_user_can_leave_a_review(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $pinID = $this->createPin()->decodeResponseJson()['uuid'];

        $attributes = new Fluent([
            'rating' => random_int(1, 5),
            'message' => $this->faker->sentence
        ]);

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews', $attributes->toArray());

        $response
            ->assertCreated()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('rating', $attributes->get('rating'))
                ->where('message', $attributes->get('message'))
                ->etc()
            )
            ->assertJsonStructure([
                'uuid',
                'rating',
                'message',
                'last_action_at',
                'reviewer' => [
                    'uuid',
                    'name',
                    'avatar',
                ],
                'gallery' => [
                    '*' => [
                        'uuid',
                        'url',
                    ]
                ]
            ]);
    }

    public function test_user_can_update_own_review(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $pinID = $this->createPin()->decodeResponseJson()['uuid'];

        $attributes = new Fluent([
            'rating' => random_int(1, 5),
            'message' => $this->faker->sentence
        ]);

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews', $attributes->toArray());
        $response->assertCreated();

        $reviewID = $response->decodeResponseJson()['uuid'];

        $attributes = new Fluent([
            'rating' => random_int(1, 5),
            'message' => null
        ]);

        $response = $this->patchJson('/api/v1/pins/' . $pinID . '/reviews/' . $reviewID, $attributes->toArray());
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('rating', $attributes->get('rating'))
                ->where('message', $attributes->get('message'))
                ->etc()
            );
    }

    public function test_user_can_delete_his_review(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $pinID = $this->createPin()->decodeResponseJson()['uuid'];

        $attributes = new Fluent([
            'rating' => random_int(1, 5),
            'message' => $this->faker->sentence
        ]);

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews', $attributes->toArray());
        $response->assertCreated();

        $reviewsCount = $this->getJson('/api/v1/pins/' . $pinID)->decodeResponseJson()['reviews_count'];

        $response = $this->deleteJson('/api/v1/pins/' . $pinID . '/reviews/' . $response->decodeResponseJson()['uuid']);
        $response->assertNoContent();

        $response = $this->getJson('/api/v1/pins/' . $pinID);
        $response
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('reviews_count', $reviewsCount - 1)
                ->etc()
            );
    }

    public function test_user_cant_delete_foreign_review(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $pinID = $this->createPin()->decodeResponseJson()['uuid'];

        $attributes = new Fluent([
            'rating' => random_int(1, 5),
            'message' => $this->faker->sentence
        ]);

        $response = $this->postJson('/api/v1/pins/' . $pinID . '/reviews', $attributes->toArray());
        $response->assertCreated();

        $reviewID = $response->decodeResponseJson()['uuid'];

        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->deleteJson('/api/v1/pins/' . $pinID . '/reviews/' . $reviewID);
        $response->assertStatus(403);
    }
}
