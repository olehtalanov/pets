<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pin\UploadRequest;
use App\Http\Resources\Media\ShortResource;
use App\Models\Pin;
use App\Models\Review;
use App\Repositories\ReviewRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ReviewMediaController extends Controller
{
    public function __construct(
        protected ReviewRepository $reviewRepository
    ) {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/pins/{pin}/reviews/{review}/media",
     *     tags={"Pins"},
     *     summary="Get list of the review media.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *     @OA\Parameter(name="review", required=true, example="995037a6-60b3-4055-aa14-3513aa9824cb", in="path"),
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MediaShortResource"),
     *         )
     *     )
     * )
     *
     * @throws AuthorizationException
     */
    public function index(Pin $pin, Review $review): JsonResponse
    {
        $this->authorize('upload', $review);

        return Response::json(
            ShortResource::collection(
                $this->reviewRepository->media($review)
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/pins/{pin}/reviews/{review}/media",
     *     tags={"Pins"},
     *     summary="Upload review media.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *     @OA\Parameter(name="review", required=true, example="995037a6-60b3-4055-aa14-3513aa9824cb", in="path"),
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
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/MediaShortResource"),
     *         )
     *     )
     * )
     *
     * @throws AuthorizationException
     */
    public function store(UploadRequest $request, Pin $pin, Review $review): JsonResponse
    {
        $this->authorize('upload', $pin);

        return Response::json(
            ShortResource::collection(
                $this->reviewRepository->upload($review, $request->file('files'))
            )
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/pins/{pin}/reviews/{review}/media/{media}",
     *     tags={"Pins"},
     *     summary="Delete a pin.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path", description="UUID of pin."),
     *     @OA\Parameter(name="review", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path", description="UUID of pin."),
     *     @OA\Parameter(name="media", required=true, example="995037a6-60b3-4055-aa14-3513aa9824cc", in="path", description="UUID of media which should be removed."),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     *
     * @throws AuthorizationException
     */
    public function destroy(Pin $pin, Media $media): JsonResponse
    {
        $this->authorize('deleteMedia', $pin);

        $this->reviewRepository->destroyMedia($media);

        return Response::json(null, 204);
    }
}
