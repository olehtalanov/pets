<?php

namespace App\Http\Controllers\Api;

use App\Data\User\CoordinatesData;
use App\Exceptions\Common\ResourceNotSetException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CoordinatesRequest;
use App\Http\Requests\User\SearchRequest;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\User\SearchResultResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
    )
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     summary="Get list of users matched by filters.",
     *
     *     @OA\Parameter(name="latitude", in="path", required=false),
     *     @OA\Parameter(name="longitude", in="path", required=false),
     *     @OA\Parameter(name="radius", in="path", required=false, description="Radius in meters"),
     *     @OA\Parameter(name="animal_type_ids[]", in="path", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="breed_ids[]", in="path", required=false, @OA\Schema(type="string")),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/PaginatedResourceCollection"
     *         )
     *     )
     * )
     *
     * @throws ResourceNotSetException
     */
    public function index(SearchRequest $request): JsonResponse
    {
        return Response::json(
            new PaginatedCollection(
                $this->userRepository->search($request->safe()->collect()),
                SearchResultResource::class
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/coordinates",
     *     tags={"Users"},
     *     summary="Set auth user coordinates.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"latitude","longitude"},
     *             ref="#/components/schemas/UserCoordinatesRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="latitude", type="float", nullable=true),
     *             @OA\Property(property="longitude", type="float", nullable=true),
     *         )
     *     )
     * )
     */
    public function storeCoordinates(CoordinatesRequest $request): JsonResponse
    {
        return Response::json(
            $this->userRepository->storeCoordinates(
                CoordinatesData::from($request)
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/coordinates",
     *     tags={"Users"},
     *     summary="Get auth user coordinates.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="latitude", type="float", nullable=true),
     *             @OA\Property(property="longitude", type="float", nullable=true),
     *         )
     *     )
     * )
     */
    public function showCoordinates(): JsonResponse
    {
        return Response::json(
            $this->userRepository->showCoordinates()
        );
    }
}
