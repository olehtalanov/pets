<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\User\ShortResource;
use App\Models\Chat;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChatFullResource",
 *     type="object",
 *
 *     @OA\Property(property="users", type="object",
 *         @OA\Property(property="9966bede-7d84-452c-9278-42b4d79c8735", type="object", ref="#/components/schemas/UserShortResource"),
 *         @OA\Property(property="9966be96-ff28-49b5-9b45-d785bbdcac7c", type="object", ref="#/components/schemas/UserShortResource"),
 *     ),
 *     @OA\Property(property="items", type="object",
 *         @OA\Property(property="2023-06-29", type="array", @OA\Items(ref="#/components/schemas/ChatMessageFullResource"))
 *     ),
 *     @OA\Property(property="meta", type="object",
 *         @OA\Property(property="total", type="int"),
 *         @OA\Property(property="current", type="int"),
 *         @OA\Property(property="nextLink", type="string", nullable=true),
 *         @OA\Property(property="prevLink", type="string", nullable=true),
 *     ),
 *     )),
 * )
 */
class CurrentChatCollection extends ResourceCollection
{
    public function __construct(
        $resource,
        private readonly Chat $chat
    ) {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'users' => [
                $this->chat->owner->uuid => new ShortResource($this->chat->owner),
                $this->chat->recipient->uuid => new ShortResource($this->chat->recipient),
            ],
            'items' => MessageResource::collection(
                $this->collection->sortBy('created_at')
            )
                ->collection
                ->groupBy(fn ($item) => $item->date),
            'meta' => [
                'total' => $this->total(),
                'current' => $this->currentPage(),
                'nextLink' => $this->nextPageUrl(),
                'prevLink' => $this->previousPageUrl(),
            ],
        ];
    }
}
