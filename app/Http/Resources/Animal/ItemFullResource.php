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
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="birth_date", type="string"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="breed", type="string"),
 *     @OA\Property(property="sex", type="string"),
 *     @OA\Property(property="weight", type="string"),
 *     @OA\Property(property="avatar", type="array", @OA\Items(
 *         @OA\Property(property="thumb", type="string"),
 *         @OA\Property(property="full", type="string"),
 *     )),
 *     @OA\Property(property="activiry", type="array", @OA\Items(
 *         @OA\Property(property="notes", type="integer"),
 *         @OA\Property(property="events", type="integer"),
 *     )),
 * )
 */
class ItemFullResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'birth_date' => $this->birth_date?->toLocalDate(),
            'type' => $this->type->name,
            'breed' => $this->breed->name,
            'sex' => $this->sex->getName(),
            'weight' => "$this->weight {$this->weight_unit->getName()}",
            'avatar' => [
                'thumb' => $this->getFirstMedia('avatar')->getFullUrl('thumb'),
                'full' => $this->getFirstMedia('avatar')->getFullUrl(),
            ],
            'activity' => [
                'notes' => $this->notes_count,
                'events' => $this->events_count,
            ],
        ];
    }
}
