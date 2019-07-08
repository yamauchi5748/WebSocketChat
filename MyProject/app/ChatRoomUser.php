<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoomUser extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'room_id', 'user_id', 'checked_at'
    ];

    // idをキャスト
    protected $casts = [
        'user_id' => 'string',
        'room_id' => 'string'
    ];
}
