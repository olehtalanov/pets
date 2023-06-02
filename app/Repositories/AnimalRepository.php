<?php

namespace App\Repositories;

use App\Data\Animal\AnimalData;
use App\Http\Resources\Animal\ItemFullResource;
use App\Http\Resources\Animal\ListItemResource;
use App\Models\Animal;
use Auth;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnimalRepository
{
    public static function make(): static
    {
        return new static();
    }

    public function list(): AnonymousResourceCollection
    {
        return ListItemResource::collection(
            Auth::user()?->animals()
                ->with(['type'])
                ->withCount(['notes', 'events'])
                ->get()
        );
    }

    public function one(string $animal): ItemFullResource
    {
        return new ItemFullResource(
            Auth::user()?->animals()
                ->whereUuid($animal)
                ->with(['type', 'breed'])
                ->withCount(['notes', 'events'])
                ->firstOrFail()
        );
    }

    public function store(AnimalData $attributes): ItemFullResource
    {
        return new ItemFullResource(
            Auth::user()?->animals()->create($attributes->toArray())
        );
    }

    public function update(string $animal, AnimalData $attributes): ItemFullResource
    {
        Animal::whereUuid($animal)->update($attributes->toArray());

        return new ItemFullResource(
            Animal::findUOrFail($animal)
        );
    }

    public function destroy(string $animal): void
    {
        Animal::whereUuid($animal)->delete();
    }
}
