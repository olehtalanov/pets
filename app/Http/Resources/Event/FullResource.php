<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\Animal\FullResource as AnimalFullResource;
use App\Http\Resources\Dictionary\TypedResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="EventFullResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="title", type="string", example="Some title"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="starts_at", type="string", example="2023-06-10 10:00:00"),
 *     @OA\Property(property="ends_at", type="string", example="2023-06-12 18:00:00"),
 *     @OA\Property(property="repeat", type="string", example="never"),
 *     @OA\Property(property="whole_day", type="boolean", example=false),
 *     @OA\Property(property="animal", type="object", ref="#/components/schemas/AnimalFullResource"),
 *     @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
 *     )),
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
            'title' => $this->title,
            'description' => $this->description,
            'starts_at' => $this->starts_at?->toDateTimeString(),
            'ends_at' => $this->ends_at?->toDateTimeString(),
            'repeat' => [
                'scheme' => $this->repeat_scheme->value,
                'name' => $this->repeat_scheme->getName(),
            ],
            'whole_day' => $this->whole_day,
            'animal' => new AnimalFullResource($this->animal),
            'categories' => TypedResource::collection($this->categories),
        ];
    }
}
