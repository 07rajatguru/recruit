<?php

namespace App\Http\Controllers;

use App\Notifications;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    public function getAjaxNotification(){

        $user_id = \Auth::user()->id;

        //$notificationDetails = Notifications::where('user_id',$user_id)->where('read',0)->get();

        $notifications = Notifications::getAllNotificationsByUserId($user_id,0);
//print_r($notifications);exit;
        return json_encode($notifications);
    }
    public function index(){

        $user_id = \Auth::user()->id;

        $notifications = Notifications::listAllNotificationsByUserId($user_id);

        return view('adminlte::notifications.index', array('notifications' => $notifications));
    }

    public function readNotification(){

        $user_id = \Auth::user()->id;

        Notifications::where('user_id','=',$user_id)->update(['read'=>1]);

        $response['returnvalue'] = 'valid';

        return json_encode($response);
    }
}
