<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pin\UploadRequest;
use App\Http\Resources\Media\ShortResource;
use App\Models\Pin;
use App\Repositories\PinRepository;
use Illuminate\Http\JsonResponse;
use Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PinGalleryController extends Controller
{
    public function __construct(
        protected PinRepository $pinRepository
    )
    {
        //
    }

    public function index(Pin $pin): JsonResponse
    {
        $this->authorize('upload', $pin);

        return Response::json(
            ShortResource::collection(
                $this->pinRepository->gallery($pin)
            )
        );
    }

    public function upload(UploadRequest $request, Pin $pin): JsonResponse
    {
        $this->authorize('upload', $pin);

        return Response::json(
            ShortResource::collection(
                $this->pinRepository->upload($pin, $request->files)
            )
        );
    }

    public function destroy(Pin $pin, Media $media): JsonResponse
    {
        $this->authorize('deleteMedia', $pin);

        $this->pinRepository->destroyMedia($media);

        return Response::json(null, 204);
    }
}
