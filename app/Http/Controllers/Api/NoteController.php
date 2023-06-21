<?php

namespace App\Http\Controllers\Api;

use App\Data\Animal\NoteData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Note\StoreRequest;
use App\Http\Resources\Note\FullResource;
use App\Http\Resources\Note\ShortResource;
use App\Models\Note;
use App\Repositories\NoteRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Response;

class NoteController extends Controller
{
    public function __construct(
        private readonly NoteRepository $noteRepository
    )
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/notes",
     *     tags={"Notes"},
     *     summary="Get list of the notes.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/NoteShortResource"),
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return Response::json(
            ShortResource::collection(
                $this->noteRepository->list()
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/notes/{uuid}",
     *     tags={"Notes"},
     *     summary="Get a note.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/NoteFullResource"
     *         )
     *     )
     * )
     */
    public function show(Note $note): JsonResponse
    {
        return Response::json(
            new FullResource(
                $this->noteRepository->one($note)
            )
        );
    }

    /**
     * @OA\Post(
     *     path="/api/notes",
     *     tags={"Notes"},
     *     summary="Create new note.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"animal_id","category_id","title"},
     *             ref="#/components/schemas/NoteStoreRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/NoteFullResource"
     *         )
     *     )
     * )
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return Response::json(
            new FullResource(
                $this->noteRepository->store(
                    NoteData::from($request->validated())
                )
            )
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/notes/{uuid}",
     *     tags={"Notes"},
     *     summary="Update the note.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"animal_id","category_id","title"},
     *             ref="#/components/schemas/NoteStoreRequest"
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Successful response",
     *
     *         @OA\JsonContent(
     *             ref="#/components/schemas/NoteFullResource"
     *         )
     *     )
     * )
     * @throws AuthorizationException
     */
    public function update(StoreRequest $request, Note $note): JsonResponse
    {
        $this->authorize('update', $note);

        return Response::json(
            new FullResource(
                $this->noteRepository->update(
                    $note,
                    NoteData::from($request->validated())
                )
            )
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/notes/{uuid}",
     *     tags={"Notes"},
     *     summary="Delete the note.",
     *
     *     @OA\Parameter(name="uuid", required=true, example="995037a6-60b3-4055-aa14-3513aa9824ca", in="path"),
     *
     *     @OA\Response(response=204, description="Successful response")
     * )
     * @throws AuthorizationException
     */
    public function destroy(Note $note): JsonResponse
    {
        $this->authorize('delete', $note);

        $this->noteRepository->destroy($note);

        return Response::json(null, 204);
    }
}
