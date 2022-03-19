<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WorkPlanning;
use App\User;

class AddYesterdayWorkPlanning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:yesterdaywp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Add Yesterday Work Planning';

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
        $yesterday_date = date('Y-m-d',strtotime("-1 days"));

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $operations = getenv('OPERATIONS');
        $type_array = array($recruitment,$hr_advisory,$operations);

        $users = User::getAllUsersExpectSuperAdmin($type_array);

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {
                
                $get_work_planning_res = WorkPlanning::getWorkPlanningByAddedDateAndUserID($yesterday_date,$key);

                if(isset($get_work_planning_res) && $get_work_planning_res != '') {
                }
                else {

                    $work_planning = new WorkPlanning();
                    $work_planning->added_date = $yesterday_date;
                    $work_planning->added_by = $key;
                    $work_planning->save();
                }
            }
        } 
    }
}