<?php

namespace App\Http\Controllers\Api;

use App\Data\Animal\AnimalData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Animal\AnimalStoreRequest;
use App\Repositories\AnimalRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class AnimalController extends Controller
{
    public function __construct(
        protected AnimalRepository $animalRepository,
    ) {
        //
    }

    /**
     * @OA\Get(
     *     path="/animals",
     *     tags={"animals"},
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
     *     path="/animals/{uuid}",
     *     tags={"animals"},
     *     summary="Get animal details.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/AnimalFullResource"
     *         )
     *     )
     * )
     */
    public function show(string $animal): JsonResponse
    {
        return Response::json(
            $this->animalRepository->one($animal)
        );
    }

    /**
     * @OA\Post(
     *     path="/animals",
     *     tags={"animals"},
     *     summary="Create new animal.",
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
    public function store(AnimalStoreRequest $request): JsonResponse
    {
        return Response::json(
            $this->animalRepository->store(
                AnimalData::from($request->validated())
            )
        );
    }
}
