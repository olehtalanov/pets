<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\User\ShortResource as UserShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReviewShortResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="rating", type="float"),
 *     @OA\Property(property="message", type="string", nullable=true),
 *     @OA\Property(property="last_action_at", type="string"),
 *     @OA\Property(property="reviewer", type="object", ref="#/components/schemas/UserShortResource"),
 * )
 */
class ShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'rating' => $this->rating,
            'message' => $this->message,
            'last_action_at' => $this->updated_at->toDateTimeString(),
            'reviewer' => new UserShortResource($this->reviewer),
        ];
    }
}
