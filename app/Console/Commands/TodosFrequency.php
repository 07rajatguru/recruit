<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TodoFrequency;
use App\ToDos;
use DB;
use App\Events\NotificationEvent;
use App\Events\NotificationMail;
use App\User;
use App\Date;

class TodosFrequency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:frequency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Todo Frequency';

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
        $todo_frequency = TodoFrequency::gettodobyfrequency();
        //echo '<pre>'; print_r($todo_frequency);exit;

            foreach ($todo_frequency as $key => $value) {

                //$user_id = \Auth::user()->id;
                $inprogress = env('INPROGRESS');

                $reminder = $value['reminder'];
                $due_date = $value['due_date'];
                $task_owner = $value['task_owner'];
                $cc_user = $value['cc_user'];
                $user_ids = $value['user_ids'];
                //$start_date = $value->start_date;

                $userid = explode(',', $user_ids);
                
                // Daily Reminder
                if ($reminder == 1) {

                    $start_date = date('Y-m-d h:i:s');
                    $start_time = explode(" ", $start_date);
                    $time = Date::converttime($start_time[1]);
                    $post_date = date('Y-m-d h:i:s' ,$time);

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date tomorrow"));
                    $todos->start_date = $post_date;
                    $todos->save();

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$post_date tomorrow"));
                    $todo_reminder_id = $value['id'];

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id = $todo_reminder_id");
                }

                // Weekly Reminder
                if ($reminder == 2) {

                    $start_date = date('Y-m-d h:i:s');
                    $start_time = explode(" ", $start_date);
                    $time = Date::converttime($start_time[1]);
                    $post_date = date('Y-m-d h:i:s' ,$time);

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date +1 week"));
                    $todos->start_date = $post_date;
                    $todos->save();

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$post_date +1 week"));
                    $todo_reminder_id = $value['id'];

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id = $todo_reminder_id");
                }

                // Monthly Reminder
                if ($reminder == 3) {

                    $start_date = date('Y-m-d h:i:s');
                    $start_time = explode(" ", $start_date);
                    $time = Date::converttime($start_time[1]);
                    $post_date = date('Y-m-d h:i:s' ,$time);

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date +1 month"));
                    $todos->start_date = $post_date;
                    $todos->save();

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$post_date +1 month"));
                    $todo_reminder_id = $value['id'];

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id = $todo_reminder_id");
                }

                // Quarterly Reminder
                if ($reminder == 4) {

                    $start_date = date('Y-m-d h:i:s');
                    $start_time = explode(" ", $start_date);
                    $time = Date::converttime($start_time[1]);
                    $post_date = date('Y-m-d h:i:s' ,$time);

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date +3 month"));
                    $todos->start_date = $post_date;
                    $todos->save();

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$post_date +3 month"));
                    $todo_reminder_id = $value['id'];

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id = $todo_reminder_id");
                }

                // Yearly Reminder
                if ($reminder == 5) {

                    /*$todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date +1 year"));
                    $todos->start_date = date('Y-m-d h:i:s');
                    $todos->save();

                    $start_date = $todos->start_date;

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$start_date +1 year"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    print_r($todo_reminder_id);exit;*/

                    $start_date = date('Y-m-d h:i:s');
                    $start_time = explode(" ", $start_date);
                    $time = Date::converttime($start_time[1]);
                    $post_date = date('Y-m-d h:i:s' ,$time);

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date +1 year"));
                    $todos->start_date = $post_date;
                    $todos->save();

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$post_date +1 year"));
                    $todo_reminder_id = $value['id'];

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id = $todo_reminder_id");
                }

                foreach ($userid as $key1=>$value1){
                    if($value1!=$task_owner){
                        $user_arr = $value1;

                        $assigned_to = User::getUserNameById($value1);
                        $assigned_to_array = explode(" ", $assigned_to);
                        $assigned_to_name = $assigned_to_array[0];
                    //print_r($assigned_to_name);exit;

                        if(isset($user_arr)/* && sizeof($user_arr)>0*/){
/*
                             print_r($user_arr);
                        exit;*/
                            // TODO : Email Notification : data store in database
                            //foreach ($users as $k=>$v){
                                $user_email = User::getUserEmailById($value1);
                                $cc_email = User::getUserEmailById($task_owner);
                                $cc_user_email=User::getUserEmailById($cc_user);
                                $cc_users_array=array($cc_email,$cc_user_email);

                                $module = "Todos";
                                $sender_name = $task_owner;
                                $message = "$assigned_to_name: New task has been assigned to you";
                                $to = $user_email;

                                $cc_users_array = array_filter($cc_users_array);
                                $cc = implode(",",$cc_users_array);
                                $subject = $message;
                                $body_message = "";
                                $module_id = $value['id'];

                                event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

                                // Notification entry
                                //$module_id = $value['id'];
                                //$module = 'Todos';
                                //$message = "$assigned_to_name: New task has been assigned to you";
                                $link = route('todos.index');

                                event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));
                            //}
                        }
                    }
                }

                //$task_owner_name = User::getUserNameById($task_owner);
                //echo $task_owner_name;exit;
                /*$user_arr = array();
                foreach ($userid as $key1=>$value1){
                    if($value1!=$task_owner){
                        $user_arr[]= $value1;

                        $assigned_to = User::getUserNameById($user_arr);
                        $assigned_to_array = explode(" ", $assigned_to);
                        $assigned_to_name = $assigned_to_array[0];
                    }
                }

                if(isset($user_arr) && sizeof($user_arr)>0){
                    $module_id = $value['id'];
                    $module = 'Todos';
                    $message = "$assigned_to_name; New task has been assigned to you";
                    $link = route('todos.index');
//echo $message;exit;
                    event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

                    // TODO : Email Notification : data store in database
                    foreach ($userid as $k=>$v){
                        $user_email = User::getUserEmailById($v);
                        $cc_email = User::getUserEmailById($task_owner);
                        $module = "Todos";
                        $sender_name = $task_owner;
                        $to = $user_email;
                        $cc = $cc_email;
                        $subject = $message;
                        $body_message = "";
                        $module_id = $value['id'];

                        event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));
                    }

                }*/
            }
    }
}
