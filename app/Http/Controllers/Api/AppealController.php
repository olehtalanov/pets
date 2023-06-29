<?php

namespace App\Http\Controllers\Api;

use App\Data\User\AppealData;
use App\Events\User\AppealAdded;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AppealRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class AppealController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/appeals",
     *     tags={"Users"},
     *     summary="Send contact us request.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message"},
     *             ref="#/components/schemas/AppealRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=204, description="Notification has been sent.")
     * )
     */
    public function __invoke(AppealRequest $request): JsonResponse
    {
        $appeal = UserRepository::make()->storeAppeal(
            AppealData::from($request->validated())
        );

        event(new AppealAdded($appeal));

        return Response::json(null, 204);
    }
}
