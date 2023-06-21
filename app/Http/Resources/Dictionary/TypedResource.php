<?php

namespace App\Http\Resources\Dictionary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="DictionaryTypedResource",
 *     type="object",
 *     required={"uuid", "name"},
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="name", type="string"),
 * )
 */
class TypedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];

        collect($this->getQueueableRelations())
            ->each(function (string $it) use (&$response) {
                $response[$it] = self::collection($this->$it);
            });

        return $response;
    }
}
