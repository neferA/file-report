<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Blog;

class BlogUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $blog;
    public $newData;
    public $oldData;

    /**
     * Create a new event instance.
     */
    public function __construct(Blog $blog, $newData, $oldData)
    {
        $this->blog = $blog;
        $this->newData = $newData;
        $this->oldData = $oldData;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
