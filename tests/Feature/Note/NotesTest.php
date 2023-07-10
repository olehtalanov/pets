<?php

namespace Tests\Feature\Note;

use App\Models\Animal;
use App\Models\Category;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class NotesTest extends TestCase
{
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $parentCategory = Category::factory()->create([
            'related_model' => Note::class,
        ]);

        Category::factory(10)->create([
            'related_model' => Note::class,
            'parent_id' => $parentCategory->getKey()
        ]);
    }

    public function test_user_cant_interact_notes_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/notes');

        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/notes');

        $response->assertStatus(401);
    }

    public function test_user_can_view_own_notes(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        Note::factory(10)->create([
            'user_id' => $this->user->getKey(),
            'animal_id' => $animal->getKey()
        ]);

        $response = $this->getJson('/api/v1/notes');

        $response
            ->assertStatus(200)
            ->assertJsonIsArray()
            ->assertJsonStructure([
                '*' => [
                    'uuid',
                    'title',
                    'description',
                    'animal',
                    'categories',
                    'last_action_at',
                ]
            ]);
    }

    public function test_user_can_view_single_note(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $note = Note::factory()->create([
            'user_id' => $this->user->getKey(),
            'animal_id' => $animal->getKey()
        ]);

        $response = $this->getJson('/api/v1/notes/' . $note->uuid);

        $response
            ->assertStatus(200)
            ->assertJsonIsObject()
            ->assertJsonStructure([
                'uuid',
                'title',
                'description',
                'animal',
                'categories',
                'last_action_at',
            ]);
    }

    public function test_user_can_create_note(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $categories = Category::onlyChildren(Note::class)->take(random_int(1, 5))->pluck('uuid');

        $response = $this->postJson('/api/v1/notes', [
            'animal_id' => $animal->uuid,
            'title' => $this->faker->sentence,
            'description' => implode("\n", $this->faker->sentences),
            'category_ids' => $categories,
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonIsObject()
            ->assertJsonStructure([
                'uuid',
                'title',
                'description',
                'animal' => [
                    'uuid',
                    'name',
                    'type',
                    'breed',
                    'sex',
                    'avatar',
                    'activity' => [
                        'notes',
                        'events',
                    ]
                ],
                'categories' => [
                    '*' => [
                        'uuid',
                        'name',
                    ]
                ],
                'last_action_at',
            ])
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('animal.uuid', $animal->uuid)
                ->has('categories', $categories->count())
                ->has('animal')
                ->etc()
            );
    }

    public function test_user_can_create_note_without_categories(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $response = $this->postJson('/api/v1/notes', [
            'animal_id' => $animal->uuid,
            'title' => $this->faker->sentence,
            'description' => implode("\n", $this->faker->sentences),
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonIsObject()
            ->assertJsonStructure([
                'uuid',
                'title',
                'description',
                'animal' => [
                    'uuid',
                    'name',
                    'type',
                    'breed',
                    'sex',
                    'avatar',
                    'activity' => [
                        'notes',
                        'events',
                    ]
                ],
                'categories' => [
                    '*' => [
                        'uuid',
                        'name',
                    ]
                ],
                'last_action_at',
            ])
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('animal.uuid', $animal->uuid)
                ->has('categories', 0)
                ->has('animal')
                ->etc()
            );
    }

    public function test_user_can_update_note(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $note = Note::factory()->create([
            'user_id' => $this->user->getKey(),
            'animal_id' => $animal->getKey()
        ]);

        $response = $this->patchJson('/api/v1/notes/' . $note->uuid, [
            'animal_id' => $animal->uuid,
            'title' => $title = $this->faker->sentence,
            'description' => implode("\n", $this->faker->sentences),
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonIsObject()
            ->assertJsonStructure([
                'uuid',
                'title',
                'description',
                'animal' => [
                    'uuid',
                    'name',
                    'type',
                    'breed',
                    'sex',
                    'avatar',
                    'activity' => [
                        'notes',
                        'events',
                    ]
                ],
                'categories' => [
                    '*' => [
                        'uuid',
                        'name',
                    ]
                ],
                'last_action_at',
            ])
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('title', $title)
                ->where('animal.uuid', $animal->uuid)
                ->has('animal')
                ->etc()
            );
    }

    public function test_another_user_cant_update_note(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $note = Note::factory()->create([
            'user_id' => $this->user->getKey(),
            'animal_id' => $animal->getKey()
        ]);

        $response = $this->patchJson('/api/v1/notes/' . $note->uuid, [
            'animal_id' => $animal->uuid,
            'title' => $this->faker->sentence,
            'description' => implode("\n", $this->faker->sentences),
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_note(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $note = Note::factory()->create([
            'user_id' => $this->user->getKey(),
            'animal_id' => $animal->getKey()
        ]);

        $response = $this->deleteJson('/api/v1/notes/' . $note->uuid);

        $response->assertStatus(204);
    }

    public function test_another_user_cant_delete_note(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $note = Note::factory()->create([
            'user_id' => $this->user->getKey(),
            'animal_id' => $animal->getKey()
        ]);

        $response = $this->deleteJson('/api/v1/notes/' . $note->uuid);

        $response->assertStatus(403);
    }
}
