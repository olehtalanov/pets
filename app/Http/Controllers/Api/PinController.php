<?php

namespace App\Http\Controllers\Api;

use App\Data\User\PinData;
use App\Exceptions\Common\ResourceNotSetException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pin\SearchRequest;
use App\Http\Requests\Pin\StoreRequest;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\Pin\FullResource;
use App\Http\Resources\Pin\ShortResource;
use App\Models\Pin;
use App\Repositories\PinRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class PinController extends Controller
{
    public function __construct(
        private readonly PinRepository $pinRepository
    )
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/pins",
     *     tags={"Pins"},
     *     summary="Get list of the user`s pins.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/PaginatedResourceCollection",
     *         )
     *     )
     * )
     * @throws ResourceNotSetException
     */
    public function index(): JsonResponse
    {
        return Response::json(
            new PaginatedCollection(
                $this->pinRepository->list(),
                ShortResource::class
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/pins/search",
     *     tags={"Pins"},
     *     summary="Get list of pins matched by filters.",
     *
     *     @OA\Parameter(name="latitude", in="path", required=false),
     *     @OA\Parameter(name="longitude", in="path", required=false),
     *     @OA\Parameter(name="radius", in="path", required=false),
     *     @OA\Parameter(name="type_ids[]", in="path", required=false, @OA\Schema(type="string")),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PinShortResource")
     *         )
     *     )
     * )
     */
    public function search(SearchRequest $request): JsonResponse
    {
        return Response::json(
            ShortResource::collection(
                $this->pinRepository->search($request->safe()->collect())
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/pins/{pin}",
     *     tags={"Pins"},
     *     summary="Get a pin.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/PinFullResource"
     *         )
     *     )
     * )
     */
    public function show(Pin $pin): JsonResponse
    {
        return Response::json(
            new FullResource(
                $this->pinRepository->one($pin)
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/pins",
     *     tags={"Pins"},
     *     summary="Create a new pin.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name","type_id","latitude","longitude"},
     *             ref="#/components/schemas/PinStoreRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/PinFullResource"
     *         )
     *     )
     * )
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return Response::json(
            new FullResource(
                $this->pinRepository->store(
                    PinData::from($request->validated())
                )
            )
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/pins/{pin}",
     *     tags={"Pins"},
     *     summary="Update a pin.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name","type_id","latitude","longitude"},
     *             ref="#/components/schemas/PinStoreRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/PinFullResource"
     *         )
     *     )
     * )
     * @throws AuthorizationException
     */
    public function update(StoreRequest $request, Pin $pin): JsonResponse
    {
        $this->authorize('update', $pin);

        return Response::json(
            new FullResource(
                $this->pinRepository->update(
                    $pin,
                    PinData::from($request->validated())
                )
            )
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/pins/{pin}",
     *     tags={"Pins"},
     *     summary="Delete a pin.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     *
     * @throws AuthorizationException
     */
    public function destroy(Pin $pin): JsonResponse
    {
        $this->authorize('delete', $pin);

        $this->pinRepository->destroy($pin);

        return Response::json(null, 204);
    }
}
