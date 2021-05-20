<?php

namespace App\Http\Controllers;

use App\ToDos;
use Illuminate\Http\Request;
use App\EmailsNotifications;
use App\User;
use DB;
use Date;

class EmailNotificationController extends Controller
{
	public function sendingmail() {

        $mail_res = \DB::table('emails_notification')
        ->select('emails_notification.*', 'emails_notification.id as id')->limit(1)->get();

        $mail = array();
        $i=0;

        foreach ($mail_res as $key => $value) {

            $mail[$i]['id'] = $value->id;
            $mail[$i]['module'] = $value->module;
            $mail[$i]['to'] = $value->to;
            $mail[$i]['cc'] = $value->cc;
            $mail[$i]['subject'] = $value->subject;
            $mail[$i]['message'] = $value->message;
            $mail[$i]['status'] = $value->status;
            $mail[$i]['module_id'] = $value->module_id;
            $sent_date = date('Y-m-d');

            $status = 2;

		    DB::statement("UPDATE emails_notification SET sent_date = '$sent_date', status=$status where id = $value->id");
            $i++;
        }

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;

        $input['mail'] = $mail;

        foreach ($mail as $key => $value) {

            $input['to'] = $value['to'];
            $input['cc'] = $value['cc'];
            $input['subject'] = $value['subject'];
            $input['message'] = $value['message'];
            $input['app_url'] = $app_url;
            $module_id = $value['module_id'];
            if ($value['module'] == 'Job Open') {

            }
            else if ($value['module'] == 'Todos') {

                // get todos subject and description
                $todos = ToDos::find($module_id);
                $user_name = User::getUserNameByEmail($input['to']);
                $input['todo_subject'] = $todos->subject;
                $input['description'] = $todos->description;
                $input['uname'] = $user_name;
                $input['todo_id'] = $module_id;

                \Mail::send('adminlte::emails.todomail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc'])->subject($input['subject']);
                });
            }

            foreach ($mail_res as $key) {
                $id = $key->id;
            }

            $status = 1;

            DB::statement("UPDATE emails_notification SET status=$status where id = $id");
        }
    }
}