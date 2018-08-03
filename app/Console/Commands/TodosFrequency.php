<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TodoFrequency;
use App\ToDos;
use DB;

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

            $inprogress = env('INPROGRESS');

            $todo_frequency_data = ToDos::join('todo_frequency','todo_frequency.todo_id','=','to_dos.id')
                                   ->select('to_dos.id','todo_frequency.reminder as reminder','to_dos.due_date','to_dos.start_date')
                                   ->where('todo_frequency.todo_id','=',$value['id'])
                                   ->get();

            $i = 0;
            foreach ($todo_frequency_data as $key1 => $value1) {
                $reminder[$i] = $value1->reminder;
                $due_date[$i] = $value1->due_date;
                //$start_date[$i] = $value1->start_date;
                
                if ($reminder[$i] == 1) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d',strtotime("$due_date[$i] tomorrow"));
                    $todos->start_date = date('Y-m-d');
                    $todos->save();

                    $start_date = $todos->start_date;

                    $reminder_date = date('Y-m-d',strtotime("$start_date tomorrow"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }

                if ($reminder[$i] == 2) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d',strtotime("$due_date[$i] +1 week"));
                    $todos->start_date = date('Y-m-d');
                    $todos->save();

                    $start_date = $todos->start_date;

                    $reminder_date = date('Y-m-d',strtotime("$start_date[$i] +1 week"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }

                if ($reminder[$i] == 3) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d',strtotime("$due_date[$i] +1 month"));
                    $todos->start_date = date('Y-m-d');
                    $todos->save();

                    $start_date = $todos->start_date;

                    $reminder_date = date('Y-m-d',strtotime("$start_date[$i] +1 month"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }
                $i++;
            }
        }
    }
}
