<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
