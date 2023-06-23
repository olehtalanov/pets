<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\Pin\ReviewResource as PinReviewResource;
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
 *     @OA\Property(property="pin", type="object", ref="#/components/schemas/PinReviewResource"),
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
            'pin' => new PinReviewResource($this->pin),
        ];
    }
}
