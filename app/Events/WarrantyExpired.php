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
    public $waranty;
    public $isRedAlarm;
    public $isOrangeAlarm;
    public $isBlackAlarm;
     /**
     * Create a new event instance.
     *
     * @param waranty $waranty
     * @param bool $isRedAlarm
     * @param bool $isOrangeAlarm
     * @param bool $isBlackAlarm
     */
    public function __construct(waranty $waranty, bool $isRedAlarm, bool $isOrangeAlarm, bool $isBlackAlarm)
    {
        $this->waranty = $waranty;
        $this->isRedAlarm = $isRedAlarm;
        $this->isOrangeAlarm = $isOrangeAlarm;
        $this->isBlackAlarm = $isBlackAlarm;
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
