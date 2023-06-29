<?php

namespace App\Http\Resources\Chat;

use Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChatMessageFullResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string", example="9986e68c-fd37-41a0-8aa3-6c03625366ba"),
 *     @OA\Property(property="owner_uuid", type="string", example="9966be96-ff28-49b5-9b45-d785bbdcac7c"),
 *     @OA\Property(property="message", type="string", example="Hello"),
 *     @OA\Property(property="time", type="string", example="10:04"),
 *     @OA\Property(property="own", type="boolean", example=true),
 *     @OA\Property(property="is_read", type="boolean", example=false),
 *     @OA\Property(property="changed", type="boolean", example=false),
 *     )),
 * )
 */
class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'owner_uuid' => $this->owner_uuid,
            'message' => $this->message,
            'time' => $this->time,
            'own' => $this->user_id === Auth::id(),
            'is_read' => $this->read_at !== null,
            'changed' => $this->created_at->toAtomString() !== $this->updated_at->toAtomString(),
        ];
    }
}
