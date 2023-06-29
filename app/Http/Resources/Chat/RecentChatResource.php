<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\User\ShortResource;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChatRecentResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string", example="9986e68c-fd37-41a0-8aa3-6c03625366ba"),
 *     @OA\Property(property="interlocutor", type="object", ref="#/components/schemas/UserShortResource"),
 *     @OA\Property(property="last_message", type="object", ref="#/components/schemas/ChatLastMessageResource"),
 *     @OA\Property(property="unread", type="int", example=5, description="Number of unread messages by current user."),
 *     )),
 * )
 */
class RecentChatResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'interlocutor' => new ShortResource($this->interlocutor),
            'last_message' => new LastMessageResource($this->lastMessage),
            'unread' => $this->messages_count,
        ];
    }
}
