<?php

namespace App\Http\Resources\Pin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PinShortResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="latitude", type="float"),
 *     @OA\Property(property="longitude", type="float"),
 *     @OA\Property(property="rating", type="float"),
 *     @OA\Property(property="own_review_exists", type="bool"),
 *     @OA\Property(property="reviews_count", type="int"),
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
            'name' => $this->name,
            'type' => $this->type->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'rating' => (float)$this->reviews_avg_rating,
            'own_review_exists' => $this->own_review_id !== null,
            'reviews_count' => $this->reviews_count,
        ];
    }
}
