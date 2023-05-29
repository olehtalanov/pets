<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

trait HasUuid
{
    use HasUuids;

    public function initializeHasUuid(): void
    {
        $this->hidden[] = 'id';
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public static function findU($uuid): static
    {
        return static::where('uuid', $uuid)->first();
    }

    public static function findUOrFail($uuid): static
    {
        if (is_null($post = static::where('uuid', $uuid)->first())) {
            abort(404);
        }

        return $post;
    }
}
