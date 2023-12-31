<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\Media\ShortResource as MediaShortResource;
use App\Http\Resources\User\ShortResource as UserShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReviewFullResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="rating", type="float"),
 *     @OA\Property(property="message", type="string", nullable=true),
 *     @OA\Property(property="last_action_at", type="string"),
 *     @OA\Property(property="reviewer", type="object", ref="#/components/schemas/UserShortResource"),
 *     @OA\Property(property="gallery", type="array", @OA\Items(ref="#/components/schemas/MediaShortResource")),
 * )
 */
class FullResource extends JsonResource
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
            'gallery' => MediaShortResource::collection($this->getMedia('gallery')),
        ];
    }
}
