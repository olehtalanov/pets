<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\CurrentChatCollection;
use App\Http\Resources\Chat\RecentChatResource;
use App\Http\Resources\PaginatedCollection;
use App\Models\Chat;
use App\Repositories\ChatRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class ChatController extends Controller
{
    public function __construct(
        protected ChatRepository $chatRepository
    ) {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/chats",
     *     tags={"Chat"},
     *     summary="Get chat list.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/PaginatedResourceCollection",
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return Response::json(
            new PaginatedCollection(
                $this->chatRepository->recent(),
                RecentChatResource::class
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/chats/{chat}",
     *     tags={"Chat"},
     *     summary="Get chat paginated messages.",
     *
     *     @OA\Parameter(name="chat", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ChatFullResource"),
     *         )
     *     )
     * )
     */
    public function show(Chat $chat): JsonResponse
    {
        $chat->load(['owner', 'recipient']);

        return Response::json(
            new CurrentChatCollection(
                $this->chatRepository->current($chat),
                $chat
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/chats",
     *     tags={"Chat"},
     *     summary="Check if user has unread chats.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(type="boolean", example=true)
     *     )
     * )
     */
    public function ping(): JsonResponse
    {
        return Response::json(
            $this->chatRepository->ping()
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/chats/{chat}",
     *     tags={"Chat"},
     *     summary="Delete the chat.",
     *     @OA\Parameter(name="chat", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     */
    public function destroy(Chat $chat): JsonResponse
    {
        $this->chatRepository->destroy($chat);

        return Response::json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/chats/{chat}/restore",
     *     tags={"Chat"},
     *     summary="Restore previously deleted chat.",
     *     @OA\Parameter(name="chat", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     */
    public function restore(string $chat): JsonResponse
    {
        $this->chatRepository->restore($chat);

        return Response::json(null, 204);
    }
}
