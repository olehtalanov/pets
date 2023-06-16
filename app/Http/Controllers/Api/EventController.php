<?php

namespace App\Http\Controllers\Api;

use App\Data\Animal\EventData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\ListRequest;
use App\Http\Requests\Event\StoreRequest;
use App\Http\Resources\Event\EventFullResource;
use App\Http\Resources\Event\EventShortResource;
use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class EventController extends Controller
{
    public function __construct(
        private readonly EventRepository $eventRepository
    ) {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Get list of events.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/EventShortResource"),
     *         )
     *     )
     * )
     */
    public function index(ListRequest $request): JsonResponse
    {
        return Response::json(
            EventShortResource::collection(
                $this->eventRepository->list(
                    $request->safe()->collect()
                )
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/events/{uuid}",
     *     tags={"Events"},
     *     summary="Get single event.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/EventFullResource"),
     *         )
     *     )
     * )
     */
    public function show(Event $event): JsonResponse
    {
        return Response::json(
            new EventFullResource(
                $this->eventRepository->one($event)
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Create new event.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"animal_id","title","repeat_scheme"},
     *             ref="#/components/schemas/EventStoreRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/EventFullResource"
     *         )
     *     )
     * )
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return Response::json(
            new EventFullResource(
                $this->eventRepository->store(
                    EventData::from($request->validated())
                )
            )
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/events/{uuid}",
     *     tags={"Events"},
     *     summary="Update new event.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"animal_id","title","repeat_scheme"},
     *             ref="#/components/schemas/EventStoreRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/EventFullResource"
     *         )
     *     )
     * )
     */
    public function update(StoreRequest $request, Event $event): JsonResponse
    {
        $this->authorize('update', $event);

        return Response::json(
            new EventFullResource(
                $this->eventRepository->update(
                    $event,
                    EventData::from($request->validated()),
                    $request->boolean('only_this')
                )
            )
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{uuid}",
     *     tags={"Events"},
     *     summary="Delete an event.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     */
    public function destroy(Event $event): JsonResponse
    {
        $this->authorize('delete', $event);

        $this->eventRepository->destroy($event);

        return Response::json(null, 204);
    }
}