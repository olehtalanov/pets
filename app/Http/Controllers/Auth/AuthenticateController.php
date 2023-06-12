<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PersonalCodeRequest;
use App\Http\Resources\User\UserResource;
use App\Mail\Auth\PersonalCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Mail;
use OpenApi\Annotations as OA;
use Response;

class AuthenticateController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/code",
     *     tags={"Auth"},
     *     summary="Get auth code.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email"},
     *
     *             @OA\Property(property="email", type="string", example="expample@mail.com"),
     *         )
     *     ),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     */
    public function code(PersonalCodeRequest $request): JsonResponse
    {
        $request->authenticate();

        /** @var User $user */
        $user = User::whereEmail($request->input('email'))->first();

        $code = $user->accessCodes()->create();

        Mail::to($user)->send(new PersonalCode($code));

        return Response::json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Authenticate user.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"code","email","device_name"},
     *
     *             @OA\Property(property="code", type="string", example="123456"),
     *             @OA\Property(property="email", type="string", example="expample@mail.com"),
     *             @OA\Property(property="device_name", type="string", example="iPhone 14 Pro"),
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *                 @OA\Property(property="token", type="string"),
     *                 @OA\Property(property="profile", type="object", ref="#/components/schemas/UserFullResource"),
     *             ),
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        /** @var User $user */
        $user = User::whereEmail($request->input('email'))->first();

        $user->tokens()->where('name', $request->input('device_name'))->delete();

        return Response::json([
            'token' => $user->createToken($request->input('device_name'))->plainTextToken,
            'profile' => new UserResource($user),
        ]);
    }
}
