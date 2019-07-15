<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;

class RoomRecieved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    var $user;
    var $room;
    /**
     * Create a new event instance.
     *
     * @param array $message
     */
    public function __construct(User $user, Array $room)
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
        \Log::debug("roomStore");
        \Log::debug($this->room);
        return new PrivateChannel('user.' . $this->user['id']);
    }

    public function broadcastWith()
    {
        return ['room' => $this->room];
    }
}
