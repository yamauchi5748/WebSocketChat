<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canJoinRoom($roomId)
    {
        $user_id = Auth::user()->id;

        if($user_id == 1){
            if(($roomId == 12) || ($roomId == 13) || ($roomId == 14) || ($roomId == 3)){
                return true;
            }
        }else if($user_id == 2){
            if(($roomId == 12) || ($roomId == 23) || ($roomId == 24) || ($roomId == 3)){
                return true;
            }
        }else if($user_id == 3){
            if(($roomId == 13) || ($roomId == 23) || ($roomId == 34) || ($roomId == 3)){
                return true;
            }
        }else if($user_id == 4){
            if(($roomId == 14) || ($roomId == 24) || ($roomId == 34) || ($roomId == 3)){
                return true;
            }
        }else{
            return false;
        }
    }
}
