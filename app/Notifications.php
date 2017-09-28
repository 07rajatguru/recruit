<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    public $table = "notifications";

    public function logNotifications($data){

        $module_id = $data['module_id'];
        $module = $data['module'];
        $message = $data['message'];
        $user_id = $data['user_id'];
        $read = $data['read'];
        $link = $data['link'];

        $notifications = new Notifications();
        $notifications->module_id = $module_id;
        $notifications->module = $module;
        $notifications->message = $message;
        $notifications->user_id = $user_id;
        $notifications->read = $read;
        $notifications->link = $link;
        $notifications->save();
    }
}
