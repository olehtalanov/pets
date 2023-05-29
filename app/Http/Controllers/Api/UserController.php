<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;

class UserController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        return Response::json(
            new UserResource($request->user())
        );
    }
}
