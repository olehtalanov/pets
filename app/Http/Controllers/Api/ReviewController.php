<?php

namespace App\Http\Controllers\Api;

use App\Data\User\ReviewData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreRequest;
use App\Http\Resources\Review\FullResource;
use App\Models\Pin;
use App\Models\Review;
use App\Repositories\ReviewRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewRepository $reviewRepository
    )
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/pins/{pin}/reviews",
     *     tags={"Pins", "Reviews"},
     *     summary="Get list of the reviews of the pin.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/ReviewFullResource"),
     *         )
     *     )
     * )
     */
    public function index(Pin $pin): JsonResponse
    {
        return Response::json(
            FullResource::collection(
                $this->reviewRepository->list($pin)
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/pins/{pin}/reviews",
     *     tags={"Pins", "Reviews"},
     *     summary="Add review to the pin.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"rating"},
     *             ref="#/components/schemas/ReviewStoreRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ReviewFullResource"),
     *         )
     *     )
     * )
     *
     * @throws AuthorizationException
     */
    public function store(StoreRequest $request, Pin $pin): JsonResponse
    {
        $this->authorize('create', [Review::class, $pin]);

        return Response::json(
            new FullResource(
                $this->reviewRepository->store(
                    $pin,
                    ReviewData::from($request->validated())
                )
            )
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/pins/{pin}/reviews/{review}",
     *     tags={"Pins", "Reviews"},
     *     summary="Update pin review.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *     @OA\Parameter(name="review", required=true, example="995037a6-60b3-4055-aa14-3513aa9824cb", in="path"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"rating"},
     *             ref="#/components/schemas/ReviewStoreRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ReviewFullResource"),
     *         )
     *     )
     * )
     *
     * @throws AuthorizationException
     */
    public function update(StoreRequest $request, Pin $pin, Review $review): JsonResponse
    {
        $this->authorize('update', $review);

        return Response::json(
            new FullResource(
                $this->reviewRepository->update(
                    $review,
                    ReviewData::from($request->validated())
                )
            )
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/pins/{pin}/reviews/{review}",
     *     tags={"Pins", "Reviews"},
     *     summary="Delete a pin.",
     *
     *     @OA\Parameter(name="pin", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *     @OA\Parameter(name="review", required=true, example="995037a6-60b3-4055-aa14-3513aa9824cb", in="path"),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     *
     * @throws AuthorizationException
     */
    public function destroy(Pin $pin, Review $review): JsonResponse
    {
        $this->authorize('delete', $review);

        $this->reviewRepository->destroy($review);

        return Response::json(null, 204);
    }
}
