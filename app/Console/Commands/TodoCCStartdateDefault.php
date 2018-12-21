<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ToDos;

class TodoCCStartdateDefault extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:ccstartdate';

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
        // Todo cc by default null set
        $todos = ToDos::getTodosByCCValue();
        
        foreach ($todos as $key => $value) {
            \DB::statement("UPDATE to_dos SET cc_user = NULL where id=$value");
        }

        // Todo start date by default null set
        $todos_start = ToDos::getTodosByStartDateValue();
        //print_r($todos_start);exit;
        foreach ($todos_start as $key1 => $value1) {
            \DB::statement("UPDATE to_dos SET start_date = NULL where id=$value1");
        }
    }
}
