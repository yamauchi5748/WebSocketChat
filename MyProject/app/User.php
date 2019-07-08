<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\ChatRoomUser;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // user_idをキャスト
    protected $casts = [
        'id' => 'string'
    ];

    public function canJoinRoom($roomId)
    {
        $user_id = Auth::user()->id;
        $user_rooms = ChatRoomUser::where('user_id', $user_id)->get();
        $result = false;
        foreach($user_rooms as $room) {
            if($room->room_id == $roomId){
                $result = true;
            }
        }

        return $result;
    }
}
