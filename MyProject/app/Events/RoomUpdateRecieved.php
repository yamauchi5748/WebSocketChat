<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RoomUpdateRecieved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    var $user;
    var $room;
    /**
     * Create a new event instance.
     *
     * @param array $message
     */
    public function __construct(Array $user, Array $room)
    {
        $this->user = $user;
        $this->room = $room;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        \Log::debug("aaa");
        \Log::debug($this->room);
        return new PrivateChannel('user.' . $this->user['id']);
    }

    public function broadcastWith()
    {
        return ['room' => $this->room];
    }
}
