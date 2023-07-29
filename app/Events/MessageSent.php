<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel; 
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('group.' . $this->message->group->id);
    }
}
