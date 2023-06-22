<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pin\UploadRequest;
use App\Http\Resources\Media\ShortResource;
use App\Models\Pin;
use App\Repositories\PinRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PinMediaController extends Controller
{
    public function __construct(
        protected PinRepository $pinRepository
    )
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/pins/{uuid}/media",
     *     tags={"Pins"},
     *     summary="Get list of the user pin media.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MediaShortResource"),
     *         )
     *     )
     * )
     * @throws AuthorizationException
     */
    public function index(Pin $pin): JsonResponse
    {
        $this->authorize('upload', $pin);

        return Response::json(
            ShortResource::collection(
                $this->pinRepository->media($pin)
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/pins/{uuid}/media",
     *     tags={"Pins"},
     *     summary="Upload pin media.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/PinMediaRequest")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MediaShortResource"),
     *         )
     *     )
     * )
     * @throws AuthorizationException
     */
    public function upload(UploadRequest $request, Pin $pin): JsonResponse
    {
        $this->authorize('upload', $pin);

        return Response::json(
            ShortResource::collection(
                $this->pinRepository->upload($pin, $request->file('files'))
            )
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/pins/{pin}/media/{media}",
     *     tags={"Pins"},
     *     summary="Delete a pin.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path", description="UUID of pin."),
     *     @OA\Parameter(name="media", required=true, example="995037a6-60b3-4055-aa14-3513aa9824cb", in="path", description="UUID of media which should be removed."),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     *
     * @throws AuthorizationException
     */
    public function destroy(Pin $pin, Media $media): JsonResponse
    {
        $this->authorize('deleteMedia', $pin);

        $this->pinRepository->destroyMedia($media);

        return Response::json(null, 204);
    }
}
