<?php

namespace App\Http\Resources;

use App\Exceptions\Common\ResourceNotSetException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PaginatedResourceCollection",
 *     type="object",
 *
 *     @OA\Property(property="items", type="array", @OA\Items(
 *         oneOf={
 *             @OA\Schema(ref="#/components/schemas/PinShortResource"),
 *             @OA\Schema(ref="#/components/schemas/UserSearchResource"),
 *         }
 *     )),
 *     @OA\Property(property="meta", type="object",
 *         @OA\Property(property="total", type="int"),
 *         @OA\Property(property="current", type="int"),
 *         @OA\Property(property="next", type="string", nullable=true),
 *     ),
 * )
 */
class PaginatedCollection extends ResourceCollection
{
    /**
     * @throws ResourceNotSetException
     */
    public function __construct(
        $resource,
        protected string $paginatingResource
    ) {
        parent::__construct($resource);

        $this->resource = $this->collectResource($resource);

        if (!$paginatingResource) {
            throw new ResourceNotSetException();
        }
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => $this->paginatingResource::collection($this->collection),
            'meta' => [
                'total' => $this->total(),
                'current' => $this->currentPage(),
                'next' => $this->nextPageUrl(),
            ],
        ];
    }
}
