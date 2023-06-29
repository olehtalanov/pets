<?php

namespace App\Repositories;

use App\Data\Chat\MessageData;
use App\Events\Chat\MessageCreated;
use App\Events\Chat\MessageDeleted;
use App\Events\Chat\MessageUpdated;
use App\Models\Chat;
use App\Models\Message;
use Auth;

class MessageRepository extends BaseRepository
{
    public function store(MessageData $data): Message
    {
        $chat = Chat::query()
            ->where([
                'owner_id' => Auth::id(),
                'recipient_id' => $data->recipient_id,
            ])
            ->orWhere([
                'owner_id' => $data->recipient_id,
                'recipient_id' => Auth::id(),
            ])
            ->first(['id']);

        if (!$chat) {
            $chat = Chat::create([
                'owner_id' => Auth::id(),
                'recipient_id' => $data->recipient_id,
            ]);
        }

        $message = Message::create(
            $data->additional([
                'chat_id' => $chat->getKey()
            ])->toArray()
        );

        $message->owner_uuid = Auth::user()->uuid;

        event(new MessageCreated($message));

        return $message;
    }

    public function update(Message $message, array $attributes): Message
    {
        tap($message)->update($attributes);

        $message->owner_uuid = Auth::user()->uuid;

        event(new MessageUpdated($message));

        return $message;
    }

    public function destroy(Message $message): void
    {
        $message->delete();

        event(new MessageDeleted($message));
    }

    public function markAsRead(array $ids): void
    {
        Message::query()
            ->whereIn('uuid', $ids)
            ->where('user_id', '!=', Auth::id())
            ->update(['read_at' => now()]);
    }
}
