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

class MessageRecieved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var */
    public $messages;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param array $messages
     */
    public function __construct(User $user, Array $messages)
    {
        $this->user = $user;
        $this->messages = $messages;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    { 
        return new PresenceChannel('room.'.$this->messages["room_id"]);
    }

    public function broadcastWith()
    {
        return ['messages' => $this->messages];
    }
}