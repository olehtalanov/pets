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
 *     @OA\Property(property="weight", type="string"),
 * )
 */
class ListItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type->name,
            'breed' => $this->breed->name,
            'sex' => $this->sex->getName(),
            'weight' => "$this->weight {$this->weight_unit->getName()}",
        ];
    }
}
