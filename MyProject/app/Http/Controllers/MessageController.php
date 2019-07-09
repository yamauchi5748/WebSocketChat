<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Events\MessageRecieved;
use Carbon\Carbon;
use App\Chat;
use App\ChatRoom;
use App\ChatRoomUser;

class MessageController extends Controller
{
    public function index($room_id)
    {
        $room_users = ChatRoomUser::where('room_id', $room_id)->get();
        $room = ChatRoom::where('id', $room_id)->first();
        $messages = Chat::where('room_id', $room_id)->get();


        // 既読数カウント
        foreach ($messages as $message) {
            // 自ユーザ以外は既読処理しない
            if ($message['sender_id'] != Auth::user()->id) {
                continue;
            };
            $already_read = $room->is_group ? 0 : false;
            \Log::debug($message);
            foreach ($room_users as $room_user) {
                // 自ユーザは省く
                if ($room_user->user_id == Auth::user()->id) {
                    continue;
                }
                if ($message->created_at <= $room_user->checked_at) {
                    if ($room->is_group) {
                        $already_read++;
                    } else {
                        $already_read = true;
                        break;
                    }
                }
            }
            $message->already_read = $already_read;
        }

        return $messages;
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|max:255'
        ]);
        $user = Auth::user();
        $uuid = (string) Str::uuid();
        $now = (string) Carbon::now();

        $message = [
            'id' => $uuid,
            'room_id' => $request->room_id,
            'sender_id' => $user->id,
            'message' => $request->text['body'],
            'content_type' => 'text',
            'created_at' => $now
        ];
        Chat::create($message);

        $room = ChatRoom::where('id', $request->room_id)->first();
        $message['is_group'] = $room->is_group;

        broadcast(new MessageRecieved($user, $message));
    }

    public function newIndex()
    {
        $result = [];
        $user = Auth::user();
        $rooms = ChatRoom::join('chat_room_users', 'chat_rooms.id', '=', 'chat_room_users.room_id')
            ->where('chat_room_users.user_id', $user->id)
            ->get();

        foreach ($rooms as $room) {
            $chats = Chat::where('room_id', $room->id)->get();
            $room_user_status = ChatRoomUser::where('room_id', $room->id)->where('user_id', $user->id)->first();

            foreach ($chats as $chat) {
                if ($chat->created_at > $room_user_status->checked_at) {
                    $result = Arr::add($result, $chat->id, $chat);
                    $result[$chat->id]->is_group = $room->is_group;
                }
            }
        }

        return $result;
    }

    public function fileStore($room_id, Request $request)
    {
        $user = Auth::user();
        $uuid = (string) Str::uuid();
        $now = (string) Carbon::now();
        $content_type;

        \Log::debug($request->file('image'));
        if ($request->file('image')) {
            $path = $request->file('image')->storeAs('public/images', $room_id . '.' . $user->id . '.' . $uuid . '.jpg');
            $path = Str::replaceFirst('public', 'storage', $path);
            $content_type = "image";
        } else {
            $path = $request->file('video')->storeAs('public/videos', $room_id . '.' . $user->id . '.' . $uuid . '.mp4');
            $path = Str::replaceFirst('public', 'storage', $path);
            $content_type = "video";
        }



        $chat = [
            'id' => $uuid,
            'room_id' => $request->room_id,
            'sender_id' => $user->id,
            $content_type => $path,
            'content_type' => $content_type,
            'created_at' => $now
        ];

        $room = ChatRoom::where('id', $room_id)->first();
        $chat['is_group'] = $room->is_group;

        Chat::create($chat);

        broadcast(new MessageRecieved($user, $chat));
    }
}
