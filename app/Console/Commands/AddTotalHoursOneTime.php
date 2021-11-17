<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WorkPlanning;

class AddTotalHoursOneTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:totalhours';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Add Total Hours in Work Planning module (One time script)';

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
        $work_planning_res = WorkPlanning::getWorkPlanningDetails(0,'','','');

        if(isset($work_planning_res) && sizeof($work_planning_res) > 0) {


            foreach ($work_planning_res as $key => $value) {

                
            }
        }
    }
}