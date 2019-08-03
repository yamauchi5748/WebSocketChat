<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Events\MessageRecieved;
use Carbon\Carbon;
use App\User;
use App\Chat;
use App\ChatRoom;
use App\ChatRoomUser;

class MessageController extends Controller
{
    public function index($room_id, $created_at)
    {
        if (!$created_at) {
            return [];
        }
        $messages = Chat::where('room_id', $room_id)
            ->where('created_at', '<', $created_at)
            ->orderBy('created_at', 'DESC')
            ->get();


        // 既読数カウント
        foreach ($messages as $message) {
            $message->already_read = ChatRoomUser::where('room_id', $room_id)
                ->where('user_id', '!=', Auth::id())
                ->where('checked_at', '>', $message->created_at)
                ->count();
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
        $now = (string) Carbon::now('Asia/Tokyo');

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
        $room['users'] = User::select('users.id')
            ->join('chat_room_users', 'users.id', '=', 'chat_room_users.user_id')
            ->where('chat_room_users.room_id', $request->room_id)
            ->get();
        $message['is_group'] = $room->is_group;

        foreach ($room['users'] as $user) {
            broadcast(new MessageRecieved($user, $message));
        }
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
        $now = (string) Carbon::now('Asia/Tokyo');
        $content_type = 'image';

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
        $room['users'] = User::select('users.id')
            ->join('chat_room_users', 'users.id', '=', 'chat_room_users.user_id')
            ->where('chat_room_users.room_id', $request->room_id)
            ->get();
        $chat['is_group'] = $room->is_group;

        Chat::create($chat);

        foreach ($room['users'] as $user) {
            broadcast(new MessageRecieved($user, $chat));
        }
    }
}
