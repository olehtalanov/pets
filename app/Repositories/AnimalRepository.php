<?php

namespace App\Repositories;

use App\Data\Animal\AnimalData;
use App\Http\Resources\Animal\ItemFullResource;
use App\Http\Resources\Animal\ListItemResource;
use App\Models\Animal;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AnimalRepository extends BaseRepository
{
    public function list(): AnonymousResourceCollection
    {
        return ListItemResource::collection(
            Auth::user()
                ->animals()
                ->with(['type', 'breed'])
                ->withCount([
                    'notes',
                    'events' => fn (Builder $query) => $query->actual(),
                ])
                ->get()
        );
    }

    public function one(Animal $animal): ItemFullResource
    {
        return new ItemFullResource(
            $animal
                ->load(['type', 'breed'])
                ->loadCount(['notes', 'events'])
        );
    }

    public function store(AnimalData $attributes): ItemFullResource
    {
        return new ItemFullResource(
            Auth::user()
                ->animals()
                ->create($attributes->toArray())
        );
    }

    public function update(Animal $animal, AnimalData $attributes): ItemFullResource
    {
        tap($animal)->update($attributes->toArray());

        return new ItemFullResource($animal);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function avatar(Animal $animal, UploadedFile $file): ?Media
    {
        return $animal
            ->addMedia($file)
            ->toMediaCollection('avatar');
    }

    public function destroy(Animal $animal): void
    {
        $animal->delete();
    }
}
