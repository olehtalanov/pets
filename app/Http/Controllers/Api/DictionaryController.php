<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\DictionaryRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class DictionaryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/dictionaries",
     *     tags={"dictionaries"},
     *     summary="Get list of required dictionaries.",
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *               @OA\Property(property="types", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
     *               @OA\Property(property="pins", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
     *             ),
     *         )
     *     )
     * )
     */
    public function index(DictionaryRepository $repository): JsonResponse
    {
        return Response::json(
            $repository->all()
        );
    }
}
