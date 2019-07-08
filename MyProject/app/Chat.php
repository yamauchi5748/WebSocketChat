<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id', 'room_id', 'sender_id', 'message', 'image', 'content_type' , 'already_read', 'created_at'
    ];

    // idをキャスト
    protected $casts = [
        'id' => 'string'
    ];
}
