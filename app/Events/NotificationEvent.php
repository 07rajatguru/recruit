<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationEvent
{
    use InteractsWithSockets, SerializesModels;
    public $module_id;
    public $module;
    public $message;
    public $link;
    public $user_arr;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($module_id, $module, $message, $link, $user_arr)
    {
        //
        $this->module_id = $module_id;
        $this->module = $module;
        $this->message = $message;
        $this->link = $link;
        $this->user_arr = $user_arr;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
}
