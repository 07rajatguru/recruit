<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TodoFrequency extends Model
{
    public $table = "todo_frequency";

    public static function gettodobyfrequency(){
     	$query = TodoFrequency::query();
     	$query = $query->join('to_dos','to_dos.id','=','todo_frequency.todo_id');
        $query = $query->select('todo_frequency.*','to_dos.id as id','to_dos.task_owner as task_owner','to_dos.subject as subject','to_dos.status as status','to_dos.description as desc','to_dos.type as type','to_dos.due_date as due_date','to_dos.start_date as start_date');
        $query = $query->where('todo_frequency.reminder_date','=',date('Y-m-d'));
        
        /*if ($reminder == 1) {
        	$query = $query->where('todo_frequency.reminder_date','=',date('Y-m-d', strtotime('tomorrow')));
        }
        elseif ($reminder == 2) {
        	$query = $query->where('todo_frequency.reminder','=',2);
        }
        elseif ($reminder == 3) {
        	$query = $query->where('todo_frequency.reminder','=',3);
        }*/
        $todo_reminder = $query->get();

        $to_dos = array();
        $i=0;
        foreach ($todo_reminder as $todo_reminders) {
        	$to_dos[$i]['id'] = $todo_reminders->id;
        	$to_dos[$i]['task_owner'] = $todo_reminders->task_owner;
        	$to_dos[$i]['subject'] = $todo_reminders->subject;
        	$to_dos[$i]['status'] = $todo_reminders->status;
        	$to_dos[$i]['desc'] = $todo_reminders->desc;
        	$to_dos[$i]['type'] = $todo_reminders->type;
        	$to_dos[$i]['due_date'] = $todo_reminders->due_date;
            $to_dos[$i]['start_date'] = $todo_reminders->start_date;
            $to_dos[$i]['reminder'] = $todo_reminders->reminder;

            $user_id = TodoFrequency::getAssociatedUserId($todo_reminders->id);
            $userid = '';
            foreach ($user_id as $k=>$v){
                if($userid==''){
                    $userid = $v;
                }
                else{
                    $userid .= ', '. $v ;
                }
            }
            $to_dos[$i]['user_ids'] = $userid;
        	$i++;
        }

        return $to_dos;
    }

    public static function getAssociatedUserId($todo_id){

        $query = TodoFrequency::query();
        $query = $query->join('todo_associated_users','todo_associated_users.todo_id','=','todo_frequency.todo_id');
        $query = $query->select('todo_associated_users.user_id as user_id');
        $query = $query->where('todo_associated_users.todo_id',$todo_id);
        $response = $query->get();

        $todo_ids = array();
        $i = 0;
        foreach ($response as $k=>$v){
            $todo_ids[$i] = $v->user_id;
            $i++;
        }

        return $todo_ids;
    }
}
