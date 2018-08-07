<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ToDos;
use App\Events\NotificationEvent;
use App\Events\NotificationMail;
use App\User;

class TodosFrequencyCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:frequencycheck';

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
        $todo_status = ToDos::getTodoFrequencyCheck();

        foreach ($todo_status as $key => $value) {

            $task_owner = $value['task_owner'];
            $user_ids = $value['assigned_to'];
            $userid = explode(',', $user_ids);

            foreach ($userid as $key1=>$value1){
                if($value1!=$task_owner){
            //print_r($value1);exit;
                    $user_arr= $value1;

                    $assigned_to = User::getUserNameById($value1);
                    $assigned_to_array = explode(" ", $assigned_to);
                    $assigned_to_name = $assigned_to_array[0];
                    //print_r($assigned_to_name);exit;

                    if(isset($user_arr) && sizeof($user_arr)>0){
                        $module_id = $value['id'];
                        $module = 'Todos';
                        $message = "ALERT Reminder : $assigned_to_name; New task has been assigned to you";
                        $link = route('todos.index');

                        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

                        // TODO : Email Notification : data store in database
                        $user_email = User::getUserEmailById($value1);
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
                }
            }
        }
    }
}
