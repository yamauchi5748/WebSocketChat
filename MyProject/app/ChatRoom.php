<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'group_name', 'is_group'
    ];

    // room_idをキャスト
    protected $casts = [
        'id' => 'string'
    ];
}
