<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AvatarRequest;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     tags={"Profile"},
     *     summary="Get user details.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/UserFullResource"
     *         )
     *     )
     * )
     */
    public function show(Request $request): JsonResponse
    {
        return Response::json(
            new UserResource($request->user())
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/profile",
     *     tags={"Profile"},
     *     summary="Update user profile.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"first_name","last_name"},
     *
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", example="expample@mail.com"),
     *             @OA\Property(property="phone", type="string", example="380009998877"),
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/UserFullResource"
     *         )
     *     )
     * )
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        tap($request->user())->update($request->validated());

        return Response::json(
            new UserResource($request->user())
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/profile/avatar",
     *     tags={"Profile"},
     *     summary="Update user avatar.",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 oneOf={
     *                     @OA\Schema(
     *
     *                         @OA\Property(
     *                             description="User avatar",
     *                             property="avatar",
     *                             type="string",
     *                             format="binary"
     *                         )
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="thumb", type="string"),
     *             @OA\Property(property="full", type="string")
     *         )
     *     )
     * )
     */
    public function avatar(AvatarRequest $request): JsonResponse
    {
        /** @var Media $media */
        $media = $request->user()
            ->addMedia($request->file('avatar'))
            ->toMediaCollection('avatar');

        return Response::json([
            'thumb' => $media->getFullUrl('thumb'),
            'full' => $media->getFullUrl(),
        ]);
    }
}
