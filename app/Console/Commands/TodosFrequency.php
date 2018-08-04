<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TodoFrequency;
use App\ToDos;
use DB;
use App\Events\NotificationEvent;
use App\Events\NotificationMail;
use App\User;

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
                $user_ids = $value['user_ids'];
                //$start_date = $value->start_date;

                $userid = explode(',', $user_ids);
                
                if ($reminder == 1) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date tomorrow"));
                    $todos->start_date = date('Y-m-d h:i:s');
                    $todos->save();

                    $start_date = $todos->start_date;

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$start_date tomorrow"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }

                if ($reminder == 2) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date +1 week"));
                    $todos->start_date = date('Y-m-d h:i:s');
                    $todos->save();

                    $start_date = $todos->start_date;

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$start_date +1 week"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }

                if ($reminder == 3) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date +1 month"));
                    $todos->start_date = date('Y-m-d h:i:s');
                    $todos->save();

                    $start_date = $todos->start_date;

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$start_date +1 month"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }

                if ($reminder == 4) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date +3 month"));
                    $todos->start_date = date('Y-m-d h:i:s');
                    $todos->save();

                    $start_date = $todos->start_date;

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$start_date +3 month"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }

                if ($reminder == 5) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d h:i:s',strtotime("$due_date +1 year"));
                    $todos->start_date = date('Y-m-d h:i:s');
                    $todos->save();

                    $start_date = $todos->start_date;

                    $reminder_date = date('Y-m-d h:i:s',strtotime("$start_date +1 year"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }

                $task_owner_name = User::getUserNameById($task_owner);
                $user_arr = array();
                foreach ($userid as $key1=>$value1){
                    if($value1!=$task_owner){
                        $user_arr[]= $value1;
                    }
                }

                if(isset($user_arr) && sizeof($user_arr)>0){
                    $module_id = $value['id'];
                    $module = 'Todos';
                    $message = "New task has been assigned to you by $task_owner_name";
                    $link = route('todos.index');

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
                    }

                    event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));
                }
            }
    }
}
