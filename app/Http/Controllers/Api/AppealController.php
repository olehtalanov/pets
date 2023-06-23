<?php

namespace App\Http\Controllers\Api;

use App\Data\User\AppealData;
use App\Events\User\AppealAdded;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AppealRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Response;

class AppealController extends Controller
{
    public function __invoke(AppealRequest $request): JsonResponse
    {
        $appeal = UserRepository::make()->storeAppeal(
            AppealData::from($request->validated())
        );

        event(new AppealAdded($appeal));

        return Response::json(null, 204);
    }
}
