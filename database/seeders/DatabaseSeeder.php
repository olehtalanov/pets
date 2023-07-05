<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\AnimalType;
use App\Models\Breed;
use App\Models\Category;
use App\Models\Event;
use App\Models\Note;
use App\Models\PinType;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Initial',
            'last_name' => 'Admin',
            'email' => 'oleh@webcap.com',
            'role' => UserRoleEnum::Admin,
        ]);

        AnimalType::factory(10)->create()
            ->each(fn (AnimalType $animalType) => Breed::factory(10)->create([
                'animal_type_id' => $animalType->getKey(),
            ]));

        PinType::factory(10)->create();

        Category::factory(5)->create(['related_model' => Event::class])
            ->each(fn (Category $category) => Category::factory(10)->create([
                'parent_id' => $category->id,
            ]));

        Category::factory(10)->create(['related_model' => Note::class])
            ->each(fn (Category $category) => Category::factory(10)->create([
                'parent_id' => $category->id,
            ]));
    }
}
