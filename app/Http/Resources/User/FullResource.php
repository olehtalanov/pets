<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserFullResource",
 *     type="object",
 *
 *     @OA\Property(property="uuid", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="avatar", type="array", @OA\Items(
 *         @OA\Property(property="thumb", type="string"),
 *         @OA\Property(property="full", type="string"),
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
        $media = $this->getFirstMedia('avatar');

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'avatar' => [
                'thumb' => $media?->getFullUrl('thumb'),
                'full' => $media?->getFullUrl(),
            ],
        ];
    }
}