<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use App\Notifications;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationEventListener
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
     * @param  NotificationEvent  $event
     * @return void
     */
    public function handle(NotificationEvent $event)
    {

        //
       // $user_id = \Auth::user()->id;

        $module_id = $event->module_id;
        $module = $event->module;
        $message = $event->message;
        $link = $event->link;
        $user_arr = $event->user_arr;

        if(isset($user_arr))
        {
            if(is_array($user_arr)){
                foreach ($user_arr as $userId) {
                    //if($user_id != $userId){
                        $notifications = new Notifications();
                        $notifications->module_id = $module_id;
                        $notifications->module = $module;
                        $notifications->message = $message;
                        $notifications->user_id = $userId;
                        $notifications->read = 0;
                        $notifications->link = $link;
                        $notifications->save();
                    //}
                }
            } else {
                //if($user_id != $user_arr){
                    $notifications = new Notifications();
                    $notifications->module_id = $module_id;
                    $notifications->module = $module;
                    $notifications->message = $message;
                    $notifications->user_id = $user_arr;
                    $notifications->read = 0;
                    $notifications->link = $link;
                    $notifications->save();
               // }
            }
        }
    }
}
