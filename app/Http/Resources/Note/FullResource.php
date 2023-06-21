<?php

namespace App\Http\Resources\Note;

use App\Http\Resources\Animal\ShortResource as AnimalShortResource;
use App\Http\Resources\Dictionary\TypedResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="NoteFullResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="descrpition", type="string", nullable=true),
 *     @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
 *     @OA\Property(property="animal", type="object", ref="#/components/schemas/AnimalFullResource"),
 *     @OA\Property(property="last_action_at", type="string"),
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
            'animal' => new AnimalShortResource($this->animal),
            'categories' => TypedResource::collection($this->categories),
            'last_action_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
