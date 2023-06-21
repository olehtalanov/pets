<?php

namespace App\Http\Resources\Animal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AnimalFullResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="name", type="string", example="Fluffy"),
 *     @OA\Property(property="birth_date", type="string", example="2010-10-02"),
 *     @OA\Property(property="type", type="string", example="Type"),
 *     @OA\Property(property="breed", type="string", example="Breed"),
 *     @OA\Property(property="sex", type="string", example="Male"),
 *     @OA\Property(property="weight", type="string", example=5),
 *     @OA\Property(property="avatar", type="array", @OA\Items(
 *         @OA\Property(property="thumb", type="string", nullable=true, example=null),
 *         @OA\Property(property="full", type="string", nullable=true, example=null),
 *     )),
 *     @OA\Property(property="activiry", type="array", @OA\Items(
 *         @OA\Property(property="notes", type="integer", example=2),
 *         @OA\Property(property="events", type="integer", example=12),
 *     )),
 * )
 */
class FullResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $media = $this->getFirstMedia('avatar');

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'birth_date' => $this->birth_date->toDateString(),
            'type' => $this->type->name,
            'breed' => $this->breed->name,
            'sex' => $this->sex->getName(),
            'weight' => "$this->weight {$this->weight_unit->getName()}",
            'avatar' => [
                'thumb' => $media?->getFullUrl('thumb'),
                'full' => $media?->getFullUrl(),
            ],
            'activity' => [
                'notes' => $this->notes_count,
                'events' => $this->events_count,
            ],
        ];
    }
}
