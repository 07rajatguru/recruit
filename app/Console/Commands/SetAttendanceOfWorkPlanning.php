<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WorkPlanning;

class SetAttendanceOfWorkPlanning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Set Attendance According to Evening Status of Work Planning';

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
        $from_date = date('Y-m-d 00:00:00',strtotime("-1 days"));
        $to_date = date("Y-m-d 23:59:59", strtotime("-1 days"));

        $work_planning_res = WorkPlanning::getWorkPlanningsByDate($from_date,$to_date);

        if(isset($work_planning_res) && sizeof($work_planning_res) > 0) {

            foreach ($work_planning_res as $key => $value) {

                $id = $value->id;
                
                \DB::statement("UPDATE `work_planning` SET `attendance` = 'A' WHERE id = $id");
            }
        }
    }
}