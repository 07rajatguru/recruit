<?php

namespace App\Http\Controllers;

use App\Notifications;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    public function getAjaxNotification(){

        $user_id = \Auth::user()->id;

        $notificationDetails = Notifications::where('user_id',$user_id)->where('read',0)->get();

//        print_r($notificationDetails);exit;

        $notificationArr = array();

        if(isset($notificationDetails) && sizeof($notificationDetails)>0){
            $i=0;
            foreach ($notificationDetails as $notificationDetail) {
                $notificationArr[$i]['module_id'] = $notificationDetail->module_id;
                $notificationArr[$i]['module'] = $notificationDetail->module;
                $notificationArr[$i]['message'] = $notificationDetail->message;
                $notificationArr[$i]['link'] = $notificationDetail->link;
                $i++;
            }
        }

        return json_encode($notificationArr);
    }
}
