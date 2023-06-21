<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\User\ShortResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RecentChatResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'user' => new ShortResource($this->owner),
            'last_message' => new LastMessageResource($this->lastMessage),
        ];
    }
}
