<?php

namespace App\Http\Resources\Pin;

use App\Http\Resources\User\ShortResource as UserShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PinReviewResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="latitude", type="float"),
 *     @OA\Property(property="longitude", type="float"),
 *     @OA\Property(property="rating", type="float"),
 *     @OA\Property(property="reviewer", type="object", ref="#/components/schemas/UserShortResource"),
 * )
 */
class ReviewResource extends JsonResource
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
            'reviewer' => new UserShortResource($this->user)
        ];
    }
}
