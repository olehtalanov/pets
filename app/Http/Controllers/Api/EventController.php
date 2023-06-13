<?php

namespace App\Http\Controllers\Api;

use App\Data\Animal\EventData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\ListRequest;
use App\Http\Requests\Event\StoreRequest;
use App\Http\Resources\Animal\EventResource;
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

    public function index(ListRequest $request): JsonResponse
    {
        return Response::json(
            $this->eventRepository->list($request->validated())
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
            new EventResource(
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
    public function update(StoreRequest $request, string $event): JsonResponse
    {
        return Response::json(
            new EventResource(
                $this->eventRepository->update(
                    $event,
                    EventData::from($request->validated())
                )
            )
        );
    }
}
