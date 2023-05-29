<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\User\UserShortResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RecentChatResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'user' => new UserShortResource($this->owner),
            'last_message' => new LastMessageResource($this->lastMessage),
        ];
    }
}
