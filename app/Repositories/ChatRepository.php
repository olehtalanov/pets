<?php

namespace App\Repositories;

use App\Http\Resources\Chat\MessageCollection;
use App\Http\Resources\Chat\RecentChatCollection;
use App\Models\Chat;

class ChatRepository extends BaseRepository
{
    public function recent(): RecentChatCollection
    {
        return new RecentChatCollection(
            Chat::with('lastMessage')->current()->paginate(20)
        );
    }

    public function current(Chat $chat): MessageCollection
    {
        return new MessageCollection(
            $chat->messages()->paginate(50)
        );
    }
}
