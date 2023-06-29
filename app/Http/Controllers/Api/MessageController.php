<?php

namespace App\Http\Controllers\Api;

use App\Data\Chat\MessageData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\MarkAsReadRequest;
use App\Http\Requests\Chat\MessageStoreRequest;
use App\Http\Requests\Chat\MessageUpdateRequest;
use App\Http\Resources\Chat\MessageResource;
use App\Models\Message;
use App\Repositories\MessageRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class MessageController extends Controller
{
    public function __construct(
        protected MessageRepository $messageRepository
    ) {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/v1/chats/messages",
     *     tags={"Chat"},
     *     summary="Create a new chat message.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChatMessageStoreRequest")
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/ChatMessageFullResource")
     *     )
     * )
     */
    public function store(MessageStoreRequest $request): JsonResponse
    {
        return Response::json(
            new MessageResource(
                $this->messageRepository->store(
                    MessageData::from($request->validated())
                )
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/chats/messages/mark",
     *     tags={"Chat"},
     *     summary="Mark as read specified chat messages.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChatMessageReadRequest")
     *     ),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     */
    public function mark(MarkAsReadRequest $request): JsonResponse
    {
        $this->messageRepository->markAsRead($request->input('ids'));

        return Response::json(null, 204);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/chats/messages/{message}",
     *     tags={"Chat"},
     *     summary="Update the chat message.",
     *     @OA\Parameter(name="message", required=true, example="9986e68c-fd37-41a0-8aa3-6c03625366ba", in="path"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ChatMessageUpdateRequest")
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/ChatMessageFullResource")
     *     )
     * )
     */
    public function update(MessageUpdateRequest $request, Message $message): JsonResponse
    {
        return Response::json(
            new MessageResource(
                $this->messageRepository->update(
                    $message,
                    $request->validated()
                )
            )
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/chats/messages/{message}",
     *     tags={"Chat"},
     *     summary="Delete the chat message.",
     *     @OA\Parameter(name="message", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     */
    public function destroy(Message $message): JsonResponse
    {
        $this->messageRepository->destroy($message);

        return Response::json(null, 204);
    }
}
