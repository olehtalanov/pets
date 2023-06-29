<?php

namespace App\Http\Controllers\Api;

use App\Data\User\ProfileData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AvatarRequest;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Resources\User\FullResource;
use App\Repositories\ProfileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Response;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileRepository $profileRepository
    ) {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     tags={"Profile", "Users"},
     *     summary="Get user details.",
     *
     *     @OA\Response(response=200, description="Successful response",
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
            new FullResource($request->user())
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/profile",
     *     tags={"Profile", "Users"},
     *     summary="Update user profile.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/ProfileRequest")
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/UserFullResource"
     *         )
     *     )
     * )
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        $user = $this->profileRepository->update(
            ProfileData::from($request->validated())
        );

        return Response::json(
            new FullResource($user)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/profile/avatar",
     *     tags={"Profile", "Users"},
     *     summary="Update user avatar.",
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(description="User avatar", property="avatar", type="string", format="binary")
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
        $media = $this->profileRepository->avatar($request->file('avatar'));

        return Response::json([
            'thumb' => $media->getFullUrl('thumb'),
            'full' => $media->getFullUrl(),
        ]);
    }
}
