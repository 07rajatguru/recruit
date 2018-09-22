<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ToDos;
use App\User;
use App\EmailsNotifications;
use App\UserLeave;
class EveryMinute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:everyminute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $mail_res = \DB::table('emails_notification')
            ->select('emails_notification.*', 'emails_notification.id as id')
            ->where('status','=',0)
            ->limit(1)
            ->get();

        $mail = array();
        $i = 0;
        foreach ($mail_res as $key => $value) {
            $email_notification_id = $value->id;
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

            \DB::statement("UPDATE emails_notification SET sent_date = '$sent_date', status=$status where id = $email_notification_id");
            $i++;
        }

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;

        $input['mail'] = $mail;

        $status = 1;
        
        /*print_r($mail);
        exit;*/
        foreach ($mail as $key => $value) {

            $input['to'] = $value['to'];
            $input['cc'] = $value['cc'];
            $input['subject'] = $value['subject'];
            $input['message'] = $value['message'];
            $input['app_url'] = $app_url;
            $module_id = $value['module_id'];

            if ($value['module'] == 'Job Open') 
            {
                $to_array=array();
                $to_array=explode(",",$input['to']);

                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['to_array']=$to_array;
                $input['cc_array']=array_unique($cc_array);

                $id=$value['id'];

                $job = EmailsNotifications::getShowJobs($id);

                $input['job'] = $job;

                \Mail::send('adminlte::emails.emailNotification', $input, function ($job) use($input) {
                    $job->from($input['from_address'], $input['from_name']);
                    $job->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                        });  

                /*$jobopen=JobOpen::find($module_id);
                $input['jobopen_subject'] = $jobopen->subject;
                $input['description'] = $jobopen->description;

                $user_name = User::getUserNameByEmail($input['to']);
                $input['uname'] = $user_name;
                $input['jobopen_id'] = $module_id;

                \Mail::send('adminlte::emails.todomail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc'])->subject($input['subject']);
                });
*/
                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                
            } 
            else if ($value['module'] == 'Todos') 
            {

                // get todos subject and description

                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['cc_array']=$cc_array;

                $todos = ToDos::find($module_id);

                $input['todo_subject'] = $todos->subject;
                $input['description'] = $todos->description;

                $user_name = User::getUserNameByEmail($input['to']);

                $input['uname'] = $user_name;

                $input['todo_id'] = $module_id;

                \Mail::send('adminlte::emails.todomail', $input, function ($message) use ($input) {
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                        });              
               
                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }
            else if ($value['module'] == 'Leave') 
            {
                $cc_array=array();
                $cc_array=explode(",",$input['cc']); 

                $input['cc_array']=array_unique($cc_array);

                $user_name = User::getUserNameById($module_id);

                $input['uname'] = $user_name;

                $leave = UserLeave::find($module_id);

                $input['leave_message'] = $leave->message;

                \Mail::send('adminlte::emails.leavemail', $input, function ($message) use ($input) {
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                        });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'"); 

                \DB::statement("UPDATE user_leave SET `status`='$status' where `id` = '$module_id'"); 
            }

        }
    }
}
