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
    public $to;
    public $subject;
    public $message;
    public $module_id;
    public $cc;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($module, $sender_name, $to, $subject, $message, $module_id,$cc)
    {
        //
        $this->module = $module;
        $this->sender_name = $sender_name;
        $this->to = $to;
        $this->cc = $cc;
        $this->subject = $subject;
        $this->message = $message;
        $this->module_id = $module_id;
        
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
