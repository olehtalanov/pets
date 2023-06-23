<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Animal\ShortenResource as AnimalResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserSearchResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="latitude", type="float"),
 *     @OA\Property(property="longitude", type="float"),
 *     @OA\Property(property="avatar", type="string"),
 *     @OA\Property(property="animals", type="array", @OA\Items(ref="#/components/schemas/AnimalShortenResource")),
 * )
 */
class SearchResultResource extends JsonResource
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
            'avatar' => $this->getFirstMedia('avatar')?->getFullUrl('thumb'),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'animals' => AnimalResource::collection($this->animals)
        ];
    }
}
