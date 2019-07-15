<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Events\Alreadyread;
use App\Events\RoomRecieved;
use App\Events\RoomUpdateRecieved;
use App\User;
use App\Chat;
use App\ChatRoom;
use App\ChatRoomUser;

class ChatRoomController extends Controller
{
    public function index()
    {
        $rooms = ChatRoom::select('chat_rooms.id', 'chat_rooms.group_name', 'chat_rooms.is_group', 'chat_rooms.admin', 'chat_rooms.created_at')
            ->join('chat_room_users', 'chat_rooms.id', '=', 'chat_room_users.room_id')
            ->where('chat_room_users.user_id', Auth::id())
            ->orderBy('chat_rooms.created_at', 'DESC')
            ->get();

        foreach ($rooms as $room) {
            $room['users'] = [];
            if ($room->is_group) {
                $room['users'] = User::select('users.id', 'users.name')
                    ->join('chat_room_users', 'users.id', '=', 'chat_room_users.user_id')
                    ->where('room_id', $room['id'])
                    ->get();
            } else {
                $room['users'] = [
                    0 => User::select('users.id', 'users.name')
                        ->join('chat_room_users', 'users.id', '=', 'chat_room_users.user_id')
                        ->where('room_id', $room['id'])
                        ->where('user_id', Auth::id())
                        ->first(),
                    1 => User::select('users.id', 'users.name')
                        ->join('chat_room_users', 'users.id', '=', 'chat_room_users.user_id')
                        ->where('room_id', $room['id'])
                        ->where('user_id', '!=', Auth::id())
                        ->first()

                ];
            }

            $room['contents'] = Chat::where('room_id', $room['id'])
                ->take(10)
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        return $rooms;
    }

    public function store(Request $request)
    {
        \Log::debug($request->join_users);
        $admin = null;
        $request->is_group ? $admin = Auth::id() : null;

        $uuid = (string) Str::uuid();
        $room = [
            'id' => $uuid,
            'group_name' => $request->group_name,
            'is_group' => $request->is_group,
            'admin' => $admin,
            'created_at' => (string) Carbon::now()
        ];

        ChatRoom::create($room);
        $room['contents'] = [];
        $room['users'] = User::select('users.id', 'users.name')
            ->whereIn('id', $request->join_users)
            ->get();

        \Log::debug($request->join_users);
        foreach ($request->join_users as $user_id) {
            ChatRoomUser::create([
                'room_id' => $room['id'],
                'user_id' => $user_id,
                'checked_at' => Carbon::now()
            ]);
            $user = User::where('id', $user_id)->first();
            broadcast(new RoomRecieved($user, $room));
        }
    }

    public function update($room_id, Request $request)
    {

        $chat_room = ChatRoom::where('id', $room_id)
            ->first();

        if (!$chat_room->admin == Auth::id()) {
            return 'timpo';
        }

        $chat_room->group_name = $request->name;
        $chat_room->save();

        $room = [
            'id' => $chat_room->id,
            'group_name' => $chat_room->group_name,
            'is_group' => $chat_room->is_group,
            'admin' => $chat_room->admin,
            'created_at' => $chat_room->created_at
        ];

        $room['contents'] = Chat::where('room_id', $room_id)
            ->take(10)
            ->orderBy('created_at', 'DESC')
            ->get();
        $room['users'] = User::select('users.id', 'users.name')
            ->whereIn('id', $request->users)
            ->get();

        ChatRoomUser::where('room_id', $room_id)->delete();
        foreach ($request->users as $user) {
            ChatRoomUser::create([
                'room_id' => $room_id,
                'user_id' => $user['id'],
                'checked_at' => Carbon::now()
            ]);

            broadcast(new RoomUpdateRecieved($user, $room));
        }
    }

    public function destroy($room_id)
    {
        $user = Auth::user();

        $room = ChatRoom::select('admin')
            ->where('id', $room_id)
            ->first();

        if ($user->id == $room->admin) {
            Chat::where('room_id', $room_id)->delete();
            ChatRoom::where('id', $room_id)->delete();
            ChatRoomUser::where('room_id', $room_id)->delete();

            return $this->index();
        } else {
            return 'no authorization';
        }
    }
    public function exit($room_id, $user_id)
    {
        if ($user_id != Auth::id()) {
            return 'エッチ';
        }

        $room = ChatRoom::select('id', 'admin')
            ->where('id', $room_id)
            ->first();
        if ($user_id == $room->admin) {
            Chat::where('room_id', $room_id)->delete();
            ChatRoom::where('id', $room_id)->delete();
            ChatRoomUser::where('room_id', $room_id)->delete();

            return $this->index();
        } else {
            ChatRoomUser::where('room_id', $room_id)->where('user_id', $user_id)->delete();

            return $this->index();
        }
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
