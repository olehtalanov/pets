<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Response;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/me",
     *     tags={"users"},
     *     summary="Get user details.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/UserFullResource"),
     *         )
     *     )
     * )
     */
    public function me(Request $request): JsonResponse
    {
        return Response::json(
            new UserResource($request->user())
        );
    }
}
