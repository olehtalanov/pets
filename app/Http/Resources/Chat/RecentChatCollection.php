<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RecentChatCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'items' => RecentChatResource::collection($this->collection),
            'links' => [
                'nextLink' => $this->nextPageUrl(),
                'prevLink' => $this->previousPageUrl(),
            ],
        ];
    }
}
