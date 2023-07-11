<?php

namespace App\Http\Resources\Pin;

use App\Http\Resources\Media\ShortResource as MediaShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PinFullResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="latitude", type="float"),
 *     @OA\Property(property="longitude", type="float"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="address", type="string"),
 *     @OA\Property(property="contact", type="string"),
 *     @OA\Property(property="rating", type="float"),
 *     @OA\Property(property="own_review_exists", type="bool"),
 *     @OA\Property(property="reviews_count", type="int"),
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
            'name' => $this->name,
            'type' => $this->type->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'description' => $this->description,
            'address' => $this->address,
            'contact' => $this->contact,
            'rating' => (float)$this->reviews_avg_rating,
            'own_review_exists' => $this->own_review_id !== null,
            'reviews_count' => $this->reviews_count,
            'gallery' => MediaShortResource::collection($this->getMedia('gallery')),
        ];
    }
}
