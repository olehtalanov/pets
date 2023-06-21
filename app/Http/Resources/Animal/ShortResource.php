<?php

namespace App\Http\Resources\Animal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AnimalListItemResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="breed", type="string"),
 *     @OA\Property(property="sex", type="string"),
 *     @OA\Property(property="avatar", type="string", nullable=true),
 *     @OA\Property(property="activiry", type="array", @OA\Items(
 *         @OA\Property(property="notes", type="integer"),
 *         @OA\Property(property="events", type="integer"),
 *     )),
 * )
 */
class ShortResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type->name,
            'breed' => $this->breed->name,
            'sex' => $this->sex->getName(),
            'avatar' => $this->getFirstMedia('avatar')?->getFullUrl('thumb'),
            'activity' => [
                'notes' => $this->notes_count,
                'events' => $this->events_count,
            ],
        ];
    }
}
