<?php

namespace App\Repositories;

use App\Enums\EventRepeatSchemeEnum;
use App\Http\Resources\Dictionary\TypedResource;
use App\Models\AnimalType;
use App\Models\Category;
use App\Models\Event;
use App\Models\Note;
use App\Models\PinType;
use Illuminate\Database\Eloquent\Collection;

class DictionaryRepository extends BaseRepository
{
    /**
     * @return array[]
     */
    public function list(): array
    {
        return [
            'types' => [
                'animals' => TypedResource::collection($this->animalTypes()),
                'pins' => TypedResource::collection($this->pinTypes()),
            ],
            'categories' => [
                'events' => TypedResource::collection($this->eventCategories()),
                'notes' => TypedResource::collection($this->noteCategories()),
                'common' => TypedResource::collection($this->commonCategories()),
            ],
            'repeatable' => [
                'events' => $this->repeatableEvents()
            ]
        ];
    }

    public function animalTypes(): Collection|array
    {
        return AnimalType::onlyVisible()->with([
            'breeds' => fn($query) => $query->onlyVisible()->select(),
        ])->get();
    }

    public function pinTypes(): Collection|array
    {
        return PinType::onlyVisible()->get();
    }

    public function eventCategories(): Collection|array
    {
        return Category::onlyParents(Event::class)->with('children')->get();
    }

    public function noteCategories(): Collection|array
    {
        return Category::onlyParents(Note::class)->with('children')->get();
    }

    private function commonCategories(): Collection|array
    {
        return Category::onlyParents()->with('children')->get();
    }

    public function repeatableEvents(): array
    {
        return EventRepeatSchemeEnum::getNames();
    }
}
