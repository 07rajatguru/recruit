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

    public static function getAllNotificationsByUserId($userid,$read){

        $notification_query = Notifications::query();
        $notification_query = $notification_query->where('user_id','=',$userid);

        if(isset($read) && ($read==0 || $read==1)){
            $notification_query = $notification_query->where('read','=',$read);
        }

        $notification_query = $notification_query->orderBy('id','desc');
        $notification_res = $notification_query->get();
//print_r($notification_res);exit;
        $notifications = array();

        $i=0;
        foreach ($notification_res as $key=>$value){
            $notifications[$i]['module'] = $value['module'];
            $notifications[$i]['msg'] = $value['message'];
            $notifications[$i]['read'] = $value['read'];
            $notifications[$i]['module_id'] = $value['module_id'];
            $notifications[$i]['link'] = $value['link'];
            $i++;
        }

        return $notifications;

    }

    public static function listAllNotificationsByUserId($userid){

        $notification_query = Notifications::query();
        $notification_query = $notification_query->where('user_id','=',$userid);
        $notification_query = $notification_query->orderBy('id','desc');
        $notification_res = $notification_query->get();
//print_r($notification_res);exit;
        $notifications = array();

        $i=0;
        foreach ($notification_res as $key=>$value){
            $notifications[$i]['module'] = $value['module'];
            $notifications[$i]['msg'] = $value['message'];
            $notifications[$i]['read'] = $value['read'];
            $notifications[$i]['module_id'] = $value['module_id'];
            $notifications[$i]['link'] = $value['link'];
            $i++;
        }

        return $notifications;

    }

}
