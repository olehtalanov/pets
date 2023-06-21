<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\User\ShortResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LastMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'is_read' => $this->reat_at !== null,
            'owner' => new ShortResource($this->user),
        ];
    }
}
