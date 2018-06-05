<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailsNotifications;
use DB;
use Date;

class EmailNotificationController extends Controller
{
	public function sendingmail(){


        $mail_res = \DB::table('emails_notification')
                    ->select('emails_notification.*', 'emails_notification.id as id')
                    //->offset(6)
                    ->limit(1)
                    ->get();

        $mail = array();
        $i=0;
        foreach ($mail_res as $key => $value) {
            $mail[$i]['id'] = $value->id;
            $mail[$i]['module'] = $value->module;
            $mail[$i]['to'] = $value->to;
            $mail[$i]['subject'] = $value->subject;
            $mail[$i]['message'] = $value->message;
            $mail[$i]['status'] = $value->status;
            $sent_date = date('YY-mm-dd');
            //echo $sent_date;exit;
            $status = 2;

		    DB::statement("UPDATE emails_notification SET sent_date = $sent_date, status=$status where id = $value->id");
            $i++;
        }
        


        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;

        $input['mail'] = $mail;

        foreach ($mail as $key => $value) {
            $input['to'] = $value['to'];
            $input['subject'] = $value['subject'];
            $input['message'] = $value['message'];
        }
        //print_r($input['message']);exit;


        \Mail::send('adminlte::emails.emailNotification', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject($input['subject']);
        });

        foreach ($mail_res as $key) {
            $id = $key->id;
        }
        $status = 1;

        DB::statement("UPDATE emails_notification SET status=$status where id = $id");

//        return view('adminlte::emails.emailNotification', compact('mail'));
    }
}
