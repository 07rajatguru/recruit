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

        return json_encode($notifications);
    }
    public function index(){

        $user_id = \Auth::user()->id;

        $notifications = Notifications::getAllNotificationsByUserId($user_id,'');

        return view('adminlte::notifications.index', array('notifications' => $notifications));
    }
}
