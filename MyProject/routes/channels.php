<?php

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('user.{user_id}.room.{room_id}', function ($user, $user_id, $room_id) {
    if ($user->canJoinRoom($room_id)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});
Broadcast::channel('user.{user_id}', function ($user, $user_id) {
    \Log::debug('channel');
    \Log::debug(Auth::id() == $user_id);
    if ($user_id == Auth::id()) {
        return $user;
    }
});
