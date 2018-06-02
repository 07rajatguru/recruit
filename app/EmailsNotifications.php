<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailsNotifications extends Model
{
    //
    use SoftDeletes;

    public $table = "emails_notification";

    public function logNotifications($data){

        $module = $data['module'];
        $sender_name = $data['sender_name'];
        $to = $data['to'];
        $subject = $data['subject'];
        $message = $data['message'];

        $emailnotification = new EmailsNotifications();
        $emailnotification->module = $module;
        $emailnotification->sender_name = $sender_name;
        $emailnotification->to = $to;
        $emailnotification->subject = $subject;
        $emailnotification->message = $message;
        $emailnotification->save();
    }
}
