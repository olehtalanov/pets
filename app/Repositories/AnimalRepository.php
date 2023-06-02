<?php

namespace App\Repositories;

use App\Http\Resources\Animal\ListItemResource;
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
            Auth::user()?->animals()->with(['type', 'breed'])->get()
        );
    }
}
