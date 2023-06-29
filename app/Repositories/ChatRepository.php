<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ChatRepository extends BaseRepository
{
    public function recent(): LengthAwarePaginator
    {
        $chats = Chat::current()
            ->select([
                'chats.*',
                'last_message_at' => Message::query()
                    ->whereColumn('chat_id', 'chats.id')
                    ->select('created_at')
                    ->limit(1),
                'interlocutor_id' => Chat::selectRaw('IF (owner_id = ?, recipient_id, owner_id) AS interlocutor_id', [
                    Auth::id()
                ]),
            ])
            ->with('lastMessage')
            ->withCount([
                'messages' => fn ($builder) => $builder->unread()
            ])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        $users = User::findMany($chats->getCollection()->pluck('interlocutor_id'));

        return $chats->setCollection(
            $chats->getCollection()->transform(fn (Chat $chat) => $chat->setRelation(
                'interlocutor',
                $users->firstWhere('id', $chat->interlocutor_id)
            ))
        );
    }

    public function current(Chat $chat): LengthAwarePaginator
    {
        return $chat
            ->messages()
            ->select([
                '*',
                DB::raw('DATE(created_at) as date'),
                DB::raw('TIME_FORMAT(created_at, "%H:%i") as time'),
                'owner_uuid' => User::query()
                    ->whereColumn('user_id', 'users.id')
                    ->select('uuid')
            ])
            ->latest()
            ->paginate(50);
    }

    public function ping(): bool
    {
        return Message::whereHas('chat', static function (Builder $builder) {
            $builder->whereNull('deleted_at');
        })->unread()->exists();
    }

    public function destroy(Chat $chat): void
    {
        $chat->delete();
    }

    public function restore(string $chat): void
    {
        Chat::onlyTrashed()->whereUuid($chat)->restore();
    }
}
