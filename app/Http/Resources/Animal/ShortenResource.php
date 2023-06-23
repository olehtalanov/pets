<?php

namespace App\Http\Resources\Animal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AnimalShortenResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="breed", type="string"),
 *     @OA\Property(property="sex", type="string"),
 *     @OA\Property(property="avatar", type="string", nullable=true),
 * )
 */
class ShortenResource extends JsonResource
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
        ];
    }
}
