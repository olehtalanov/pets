<?php

namespace App\Http\Resources\Note;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="NoteShortResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="descrpition", type="string", nullable=true),
 *     @OA\Property(property="categories", type="string"),
 *     @OA\Property(property="animal", type="string"),
 *     @OA\Property(property="last_action_at", type="string"),
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
            'description' => Str::limit($this->description),
            'animal' => $this->animal_name,
            'categories' => $this->categories->pluck('name')->implode(', '),
            'last_action_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
