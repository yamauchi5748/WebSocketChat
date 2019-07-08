<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;
use App\Chat;

class AlreadyRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var */
    public $chat;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param array $message
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
        $this->user = User::where('id', $chat->sender_id)->first();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        \Log::debug($this->chat);
        return new PrivateChannel('user.'.$this->user->id.'.room.'.$this->chat["room_id"]);
    }

    public function broadcastWith()
    {
        return ['chat' => $this->chat];
    }
}