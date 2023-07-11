<?php

namespace Tests\Feature\Animal;

use App\Enums\SexEnum;
use App\Enums\WeightUnitEnum;
use App\Models\Animal;
use App\Models\Breed;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Fluent;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AnimalsTest extends TestCase
{
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_user_cant_interact_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/animals');
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/animals');
        $response->assertStatus(401);
    }

    public function test_user_can_view_own_animals(): void
    {
        $this->actingAs($this->user, 'sanctum');

        Animal::factory(5)->create([
            'user_id' => $this->user->getKey(),
        ]);

        $response = $this->getJson('/api/v1/animals');

        $response
            ->assertOk()
            ->assertJsonIsArray()
            ->assertJsonCount(5)
            ->assertJsonStructure([
                '*' => [
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
                ]
            ]);
    }

    public function test_user_can_view_single_animal(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $response = $this->getJson('/api/v1/animals/' . $animal->uuid);

        $response
            ->assertOk()
            ->assertJsonIsObject()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('uuid', $animal->uuid)
                ->where('name', $animal->name)
                ->etc()
            );
    }

    public function test_another_user_cant_view_animal(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $animal = Animal::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $response = $this->getJson('/api/v1/animals/' . $animal->uuid);

        $response->assertStatus(403);
    }

    public function test_user_can_create_animal(): void
    {
        $this->createAnimal();
    }

    public function test_user_can_update_own_animal(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->createAnimal()->decodeResponseJson();

        $attributes = new Fluent([
            'name' => $this->faker->word,
            'sex' => SexEnum::Male->value,
            'birth_date' => today()->subYears(3)->toDateString(),
            'animal_type_id' => null,
            'breed_id' => null,
            'breed_name' => $this->faker->word,
            'custom_type_name' => $this->faker->sentence(3),
            'custom_breed_name' => $this->faker->sentence(3),
            'metis' => true,
            'sterilised' => true,
            'weight' => random_int(3, 10),
            'weight_unit' => WeightUnitEnum::Pound->value
        ]);

        $response = $this->patchJson('/api/v1/animals/' . $decoded['uuid'], $attributes->toArray());

        $response->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('name', $attributes->get('name'))
                ->where('sex', SexEnum::Male->getName())
                ->where('birth_date', $attributes->get('birth_date'))
                ->where('type', str($attributes->get('custom_type_name'))->title()->toString())
                ->where('breed', str($attributes->get('custom_breed_name'))->title()->toString())
                ->where('breed_name', str($attributes->get('breed_name'))->title()->toString())
                ->where('metis', $attributes->get('metis'))
                ->where('weight', $attributes->get('weight') . ' ' . WeightUnitEnum::Pound->getName())
                ->etc()
            );
    }

    public function test_another_user_cant_update_animal(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->createAnimal()->decodeResponseJson();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $attributes = new Fluent([
            'name' => $this->faker->word,
            'sex' => SexEnum::Male->value,
            'birth_date' => today()->subYears(3)->toDateString(),
            'animal_type_id' => null,
            'breed_id' => null,
            'breed_name' => $this->faker->word,
            'custom_type_name' => $this->faker->sentence(3),
            'custom_breed_name' => $this->faker->sentence(3),
            'metis' => true,
            'sterilised' => true,
            'weight' => random_int(3, 10),
            'weight_unit' => WeightUnitEnum::Pound->value
        ]);

        $response = $this->patchJson('/api/v1/animals/' . $decoded['uuid'], $attributes->toArray());

        $response->assertStatus(403);
    }

    public function test_user_can_delete_animal(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->createAnimal()->decodeResponseJson();

        $response = $this->deleteJson('/api/v1/animals/' . $decoded['uuid']);

        $response->assertNoContent();
    }

    public function test_another_user_cant_delete_animal(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $decoded = $this->createAnimal()->decodeResponseJson();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->deleteJson('/api/v1/animals/' . $decoded['uuid']);

        $response->assertStatus(403);
    }

    public function test_user_can_upload_avatar(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $decoded = $this->createAnimal()->decodeResponseJson();

        $response = $this->postJson('/api/v1/animals/' . $decoded['uuid'] . '/avatar', [
            'avatar' => $file,
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'thumb',
                'full',
            ]);
    }

    private function createAnimal(): TestResponse
    {
        $this->actingAs($this->user, 'sanctum');

        /** @var Breed $breed */
        $breed = Breed::inRandomOrder()->first() ?? Breed::factory()->create();

        $attributes = new Fluent([
            'name' => $this->faker->word,
            'sex' => SexEnum::Female->value,
            'birth_date' => today()->subYears(2)->toDateString(),
            'animal_type_id' => $breed->type->uuid,
            'breed_id' => $breed->uuid,
            'breed_name' => $this->faker->word,
            'metis' => false,
            'sterilised' => false,
            'weight' => random_int(3, 10),
            'weight_unit' => WeightUnitEnum::Kg->value
        ]);

        $response = $this->postJson('/api/v1/animals', $attributes->toArray());

        $response
            ->assertCreated()
            ->assertJsonIsObject()
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('name', $attributes->get('name'))
                ->where('sex', SexEnum::Female->getName())
                ->where('birth_date', $attributes->get('birth_date'))
                ->where('type', $breed->type->name)
                ->where('breed', $breed->name)
                ->where('breed_name', str($attributes->get('breed_name'))->title()->toString())
                ->where('metis', $attributes->get('metis'))
                ->where('weight', $attributes->get('weight') . ' ' . WeightUnitEnum::Kg->getName())
                ->etc()
            );

        return $response;
    }
}
