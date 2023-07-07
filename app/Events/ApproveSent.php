<?php

namespace App\Events;

use App\Models\Group;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApproveSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $group;
    public $requester;
    public $approver;

    public function __construct(User $requester, User $approver, Group $group)
    {
        $this->requester = $requester;
        $this->approver = $approver;
        $this->group = $group;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('group-chat.' . $this->group->id);
    }
}