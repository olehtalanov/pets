<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;
use Str;

/**
 * @OA\Schema(
 *     schema="ChatLastMessageResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string", example="9986e68c-fd37-41a0-8aa3-6c03625366ba"),
 *     @OA\Property(property="message", type="string", example="Some unread message..."),
 *     @OA\Property(property="is_read", type="bool", example=false),
 *     )),
 * )
 */
class LastMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'message' => Str::limit($this->message, 50),
            'is_read' => $this->read_at !== null,
        ];
    }
}
