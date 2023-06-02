<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\DictionaryRepository;
use Illuminate\Http\JsonResponse;
use Response;

class DictionaryController extends Controller
{
    public function index(DictionaryRepository $repository): JsonResponse
    {
        return Response::json(
            $repository->all()
        );
    }
}
