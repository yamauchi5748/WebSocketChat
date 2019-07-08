<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Events\Alreadyread;
use App\Chat;
use App\ChatRoom;
use App\ChatRoomUser;

class ChatRoomController extends Controller
{
    public function index()
    {
        $rooms = ChatRoom::join('chat_room_users', 'chat_rooms.id', '=', 'chat_room_users.room_id')
            ->where('chat_room_users.user_id', Auth::user()->id)
            ->where('chat_rooms.is_group', true)
            ->get();

        return $rooms;
    }

    public function store(Request $request)
    {
        if (!$request->is_group) {
            // 自ユーザの参加ルームを取得
            $my_rooms = ChatRoom::join('chat_room_users', 'chat_rooms.id', '=', 'chat_room_users.room_id')
                ->where('chat_room_users.user_id', $request->join_users[1])
                ->where('chat_rooms.is_group', false)
                ->get();
            // 相手ユーザの参加ルームを取得
            $target_rooms = ChatRoom::join('chat_room_users', 'chat_rooms.id', '=', 'chat_room_users.room_id')
                ->where('chat_room_users.user_id', $request->join_users[0])
                ->where('chat_rooms.is_group', false)
                ->get();
            $result = null;

            // 一致するルームがあれば返す
            foreach ($my_rooms as $value1) {
                foreach ($target_rooms as $value2) {
                    if ($value1->room_id == $value2->room_id) {
                        $result = $value1;
                    }
                }
            }

            if ($result) {
                return $result;
            }
        }

        $uuid = (string) Str::uuid();
        $chat_room = [
            'id' => $uuid,
            'group_name' => $request->group_name,
            'is_group' => $request->is_group
        ];

        ChatRoom::create($chat_room);
        $chat_room['room_id'] = $uuid;

        foreach ($request->join_users as $user_id) {
            ChatRoomUser::create([
                'room_id' => $chat_room['id'],
                'user_id' => $user_id,
                'checked_at' => Carbon::now()
            ]);
        }

        return $chat_room;
    }

    public function checkAt(Request $request)
    {
        $room_user = ChatRoomUser::where('user_id', $request->user_id)
            ->where('room_id', $request->room_id)
            ->first();
        \Log::debug($request);
        $chats = Chat::where('room_id', $request->room_id)
            ->where('created_at', '>', $room_user->checked_at)
            ->get();

        foreach ($chats as $chat) {
            if ($chat->sender_id == $request->user_id) continue;
            broadcast(new AlreadyRead($chat));
        }

        $room_user = ChatRoomUser::where('user_id', $request->user_id)
            ->where('room_id', $request->room_id)
            ->update(['checked_at' => (string) Carbon::now()]);

        return $room_user;
    }
}
