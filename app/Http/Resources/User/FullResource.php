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
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="name", type="string", nullable=true),
 *     @OA\Property(property="first_name", type="string", nullable=true),
 *     @OA\Property(property="last_name", type="string", nullable=true),
 *     @OA\Property(property="phone", type="string", nullable=true),
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
            'email' => $this->email,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'avatar' => [
                'thumb' => $media?->getFullUrl('thumb'),
                'full' => $media?->getFullUrl(),
            ],
        ];
    }
}
