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
        $todos = ToDos::getAllTodos();
        print_r($todos);
    }
}
