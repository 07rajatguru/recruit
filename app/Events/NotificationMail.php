<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationMail
{
    use InteractsWithSockets, SerializesModels;
    public $module;
    public $sender_name;
    public $to,
    public $subject;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($module, $sender_name, $to, $subject, $message)
    {
        //
        $this->module = $module;
        $this->sender_name = $sender_name;
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    /*public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }*/
}
