<?php

namespace App\Repositories;

use App\Data\Animal\AnimalData;
use App\Http\Resources\Animal\FullResource;
use App\Http\Resources\Animal\ShortResource;
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
        return ShortResource::collection(
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

    public function one(Animal $animal): FullResource
    {
        return new FullResource(
            $animal
                ->load(['type', 'breed'])
                ->loadCount(['notes', 'events'])
        );
    }

    public function store(AnimalData $attributes): FullResource
    {
        return new FullResource(
            Auth::user()
                ->animals()
                ->create($attributes->toArray())
        );
    }

    public function update(Animal $animal, AnimalData $attributes): FullResource
    {
        tap($animal)->update($attributes->toArray());

        return new FullResource($animal);
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
