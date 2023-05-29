<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\User\UserShortResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'content' => $this->content,
            'is_read' => $this->reat_at !== null,
            'owner' => new UserShortResource($this->user),
        ];
    }
}
