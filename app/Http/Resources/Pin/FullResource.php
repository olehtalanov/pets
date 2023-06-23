<?php

namespace App\Http\Resources\Pin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
 *     @OA\Property(property="gallery", type="array", @OA\Items(type="string")),
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
            'description' => $this->address,
            'address' => $this->address,
            'contact' => $this->contact,
            'rating' => (float)$this->reviews_avg_rating,
            'gallery' => $this->getMedia('gallery')->map(fn(Media $media) => $media->getFullUrl()),
        ];
    }
}
