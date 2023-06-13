<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\DictionaryRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class DictionaryController extends Controller
{
    public function __construct(
        private readonly DictionaryRepository $dictionaryRepository
    ) {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/dictionaries",
     *     tags={"Dictionaries"},
     *     summary="Get list of required dictionaries.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *               @OA\Property(property="types", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
     *               @OA\Property(property="pins", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
     *             ),
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return Response::json(
            $this->dictionaryRepository->list()
        );
    }

    /**
     * @OA\Get(
     *     path="/api/dictionaries/repeatable",
     *     tags={"Dictionaries"},
     *     summary="Get list of event repeatable.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     )
     * )
     */
    public function repeatable(): JsonResponse
    {
        return Response::json(
            $this->dictionaryRepository->repeatable()
        );
    }
}
