<?php

namespace App\Listeners;

use App\Events\NotificationMail;
use App\Notifications;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationMailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationMail  $event
     * @return void
     */
    public function handle(NotificationMail $event)
    {
        //
        $user_id = \Auth::user()->id;

        $module = $event->module;
        $sender_name = $event->sender_name;
        $to = $event->to;
        $subject = $event->subject;
        $message = $event->message;

        $emailnotification = new EmailsNotifications();
        $emailnotification->module = $module;
        $emailnotification->sender_name = $sender_name;
        $emailnotification->to = $to;
        $emailnotification->subject = $subject;
        $emailnotification->message = $message;
        $emailnotification->save();
    }
}
