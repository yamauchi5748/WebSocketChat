<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageRecieved;
use App\User;

class MessageController extends Controller
{
    public function store(Request $request)
    {  
        $request->validate([
            'text' => 'required|max:255'
        ]);

        $messages = [
            "user_id" => $request->user_id,
            "room_id" => $request->room_id,
            "message" => $request->text
        ];

        $user = Auth::user();

        broadcast(new MessageRecieved($user, $messages));

        #return response()->json($messages);
    }
}
