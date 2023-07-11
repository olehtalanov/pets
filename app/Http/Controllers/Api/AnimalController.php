<?php

namespace App\Http\Controllers\Api;

use App\Data\Animal\AnimalData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Animal\AvatarRequest;
use App\Http\Requests\Animal\StoreRequest;
use App\Models\Animal;
use App\Repositories\AnimalRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class AnimalController extends Controller
{
    public function __construct(
        protected AnimalRepository $animalRepository,
    )
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/animals",
     *     tags={"Animals"},
     *     summary="Get list of user animals.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AnimalShortResource"),
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return Response::json(
            $this->animalRepository->list()
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/animals/{animal}",
     *     tags={"Animals"},
     *     summary="Get animal details.",
     *
     *     @OA\Parameter(name="animal", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/AnimalFullResource")
     *     )
     * )
     * @throws AuthorizationException
     */
    public function show(Animal $animal): JsonResponse
    {
        $this->authorize('view', $animal);

        return Response::json(
            $this->animalRepository->one($animal)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/animals",
     *     tags={"Animals"},
     *     summary="Create a new animal.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AnimalStoreRequest")
     *     ),
     *
     *     @OA\Response(response=201, description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/AnimalFullResource")
     *     )
     * )
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return Response::json(
            $this->animalRepository->store(
                AnimalData::from($request->validated())
            ),
            201
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/animals/{animal}",
     *     tags={"Animals"},
     *     summary="Update existing animal.",
     *
     *     @OA\Parameter(name="animal", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AnimalStoreRequest")
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/AnimalFullResource")
     *     )
     * )
     *
     * @throws AuthorizationException
     */
    public function update(StoreRequest $request, Animal $animal): JsonResponse
    {
        $this->authorize('update', $animal);

        return Response::json(
            $this->animalRepository->update(
                $animal,
                AnimalData::from($request->validated())
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/animals/{animal}/avatar",
     *     tags={"Animals"},
     *     summary="Update an animal avatar.",
     *
     *     @OA\Parameter(name="animal", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(description="Animal avatar", property="avatar", type="string", format="binary")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="thumb", type="string"),
     *             @OA\Property(property="full", type="string")
     *         )
     *     )
     * )
     *
     * @throws AuthorizationException
     */
    public function avatar(AvatarRequest $request, Animal $animal): JsonResponse
    {
        $this->authorize('update', $animal);

        try {
            $media = $this->animalRepository->avatar($animal, $request->file('avatar'));
        } catch (FileIsTooBig|FileDoesNotExist $e) {
            //
        }

        return Response::json([
            'thumb' => $media?->getFullUrl('thumb'),
            'full' => $media?->getFullUrl(),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/animals/{animal}",
     *     tags={"Animals"},
     *     summary="Delete an animal.",
     *
     *     @OA\Parameter(name="animal", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     *
     * @throws AuthorizationException
     */
    public function destroy(Animal $animal): JsonResponse
    {
        $this->authorize('delete', $animal);

        $this->animalRepository->destroy($animal);

        return Response::json(null, 204);
    }
}
