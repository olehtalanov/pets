<?php

namespace App\Data\Animal;

use App\Models\Animal;
use App\Models\Category;
use Spatie\LaravelData\Data;

class NoteData extends Data
{
    public function __construct(
        public string $title,
        public string $animal_id,
        public array $category_ids,
        public ?string $description,
    ) {
        $this->animal_id = Animal::findUOrFail($animal_id)?->getKey();
        $this->category_ids = Category::whereIn('uuid', $this->category_ids)->pluck('id')->toArray();
    }
}
