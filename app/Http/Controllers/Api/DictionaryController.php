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
    )
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/dictionaries",
     *     tags={"Dictionaries"},
     *     summary="Get list of required dictionaries.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="types",
     *                type="object",
     *                @OA\Property(property="animals", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
     *                @OA\Property(property="pins", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
     *             ),
     *             @OA\Property(
     *                property="categories",
     *                type="object",
     *                @OA\Property(property="events", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
     *                @OA\Property(property="notes", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
     *                @OA\Property(property="common", type="array", @OA\Items(ref="#/components/schemas/DictionaryTypedResource")),
     *             ),
     *             @OA\Property(
     *                property="repeatable",
     *                type="object",
     *                @OA\Property(
     *                  property="events",
     *                  type="object",
     *
     *                  @OA\Property(property="never", type="string", example="Never"),
     *                  @OA\Property(property="every_day", type="string", example="Every day"),
     *                  @OA\Property(property="every_working_day", type="string", example="Every working day"),
     *                  @OA\Property(property="every_weekend", type="string", example="Every weekend"),
     *                  @OA\Property(property="every_week", type="string", example="Every week"),
     *                  @OA\Property(property="every_month", type="string", example="Every month"),
     *                  @OA\Property(property="every_year", type="string", example="Every year"),
     *                ),
     *             ),
     *         )
     *     )
     * )
     */
    public function __invoke(): JsonResponse
    {
        return Response::json(
            $this->dictionaryRepository->list()
        );
    }
}
