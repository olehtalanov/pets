<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Repositories\ChatRepository;
use Illuminate\Http\JsonResponse;
use Response;

class ChatController extends Controller
{
    public function __construct(protected ChatRepository $chatRepository)
    {
        //
    }

    public function index(): JsonResponse
    {
        return Response::json(
            $this->chatRepository->recent()
        );
    }

    public function show(Chat $chat)
    {
        return Response::json(
            $this->chatRepository->current($chat)
        );
    }
}
