<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="EventShortResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="title", type="string", example="Some title"),
 *     @OA\Property(property="starts_at", type="string", example="2023-06-10 10:00:00"),
 *     @OA\Property(property="ends_at", type="string", example="2023-06-12 18:00:00"),
 *     @OA\Property(property="repeat", type="string", example="never"),
 *     @OA\Property(property="whole_day", type="boolean", example=false),
 *     @OA\Property(property="animal", type="string"),
 *     @OA\Property(property="categories", type="string"),
 *     )),
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
            'title' => $this->title,
            'starts_at' => $this->starts_at?->toDateTimeString(),
            'ends_at' => $this->ends_at?->toDateTimeString(),
            'repeat' => [
                'scheme' => $this->repeat_scheme->value,
                'name' => $this->repeat_scheme->getName(),
            ],
            'whole_day' => $this->whole_day,
            'animal' => $this->animal_name,
            'categories' => $this->categories->pluck('name')->implode(', '),
        ];
    }
}
