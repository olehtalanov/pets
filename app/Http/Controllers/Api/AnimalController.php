<?php

namespace App\Http\Controllers\Api;

use App\Data\Animal\AnimalData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Animal\AvatarRequest;
use App\Http\Requests\Animal\StoreRequest;
use App\Models\Animal;
use App\Repositories\AnimalRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

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
     *     path="/api/animals",
     *     tags={"Animals"},
     *     summary="Get list of user animals.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/AnimalListItemResource"),
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
     *     path="/api/animals/{uuid}",
     *     tags={"Animals"},
     *     summary="Get animal details.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/AnimalFullResource"
     *         )
     *     )
     * )
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
     *     path="/api/animals",
     *     tags={"Animals"},
     *     summary="Create a new animal.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name","sex","birth_date","breed_name","weight","weight_unit"},
     *
     *             @OA\Property(property="name", type="string", example="Fluffy"),
     *             @OA\Property(property="sex", type="string", enum={"male","female"}, example="male"),
     *             @OA\Property(property="birth_date", type="string", example="2021-06-22"),
     *             @OA\Property(property="animal_type", type="string", nullable=true, example="995037a6-5811-4ace-b1f7-4667517dd6e0"),
     *             @OA\Property(property="custom_animal_type", type="string", nullable=true, example=null),
     *             @OA\Property(property="breed", type="string", nullable=true, example=null),
     *             @OA\Property(property="custom_breed_name", type="string", nullable=true),
     *             @OA\Property(property="breed_name", type="string"),
     *             @OA\Property(property="metis", type="boolean", example=false),
     *             @OA\Property(property="weight", type="number", format="double", example="2.5"),
     *             @OA\Property(property="weight_unit", type="string", example="kg"),
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/AnimalFullResource"
     *         )
     *     )
     * )
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return Response::json(
            $this->animalRepository->store(
                AnimalData::from($request->validated())
            )
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/animals/{uuid}",
     *     tags={"Animals"},
     *     summary="Update existing animal.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name","sex","birth_date","breed_name","weight","weight_unit"},
     *
     *             @OA\Property(property="name", type="string", example="Fluffy"),
     *             @OA\Property(property="sex", type="string", enum={"male","female"}, example="male"),
     *             @OA\Property(property="birth_date", type="string", example="2021-06-22"),
     *             @OA\Property(property="animal_type", type="string", nullable=true, example="995037a6-5811-4ace-b1f7-4667517dd6e0"),
     *             @OA\Property(property="custom_animal_type", type="string", nullable=true, example=null),
     *             @OA\Property(property="breed", type="string", nullable=true, example=null),
     *             @OA\Property(property="custom_breed_name", type="string", nullable=true),
     *             @OA\Property(property="breed_name", type="string"),
     *             @OA\Property(property="metis", type="boolean", example=false),
     *             @OA\Property(property="weight", type="number", format="double", example="2.5"),
     *             @OA\Property(property="weight_unit", type="string", example="kg"),
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(ref="#/components/schemas/AnimalFullResource")
     *     )
     * )
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
     *     path="/api/animals/{uuid}/avatar",
     *     tags={"Animals"},
     *     summary="Update user avatar.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 oneOf={
     *                     @OA\Schema(
     *
     *                         @OA\Property(
     *                             description="Animal avatar",
     *                             property="avatar",
     *                             type="string",
     *                             format="binary"
     *                         )
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="thumb", type="string"),
     *             @OA\Property(property="full", type="string")
     *         )
     *     )
     * )
     */
    public function avatar(AvatarRequest $request, Animal $animal): JsonResponse
    {
        $this->authorize('update', $animal);

        $media = $this->animalRepository->avatar($animal, $request->file('avatar'));

        return Response::json([
            'thumb' => $media?->getFullUrl('thumb'),
            'full' => $media?->getFullUrl(),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/animals/{uuid}",
     *     tags={"Animals"},
     *     summary="Delete an animal.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     */
    public function destroy(Animal $animal): JsonResponse
    {
        $this->authorize('delete', $animal);

        $this->animalRepository->destroy($animal);

        return Response::json(null, 204);
    }
}
