<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\waranty;

class WarrantyExpired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $warranty;
    public $alarmColor;
     /**
     * Create a new event instance.
     *
     * @param waranty $warranty
     * @param string $alarmColor
     */
    public function __construct(waranty $warranty, string $alarmColor)
    {
        $this->warranty = $warranty;
        $this->alarmColor = $alarmColor;
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
