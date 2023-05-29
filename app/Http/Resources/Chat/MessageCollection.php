<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'items' => new MessageResource($this->collection),
            'links' => [
                'nextLink' => $this->nextPageUrl(),
                'prevLink' => $this->previousPageUrl(),
            ],
        ];
    }
}
