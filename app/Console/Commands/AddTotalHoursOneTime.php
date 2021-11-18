<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WorkPlanning;
use App\WorkPlanningList;

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

                $projected_time_array = array();
                $actual_time_array = array();

                $total_projected_time = '';
                $time_in_secs = '';
                $total_time = '';
                $hours = '';
                $minutes = '';
                $seconds = '';

                $total_actual_time = '';
                $time_in_secs_1 = '';
                $total_time_1 = '';
                $hours_1 = '';
                $minutes_1 = '';
                $seconds_1 = '';

                $wp_id = $value['id'];

                $work_planning_list = WorkPlanningList::getWorkPlanningList($wp_id);

                if(isset($work_planning_list) && sizeof($work_planning_list) > 0) {

                    foreach ($work_planning_list as $k1 => $v1) {

                        if(isset($v1['projected_time']) && $v1['projected_time'] != '') {

                            $projected_time_array[] = $v1['projected_time'];
                        }

                        if(isset($v1['actual_time']) && $v1['actual_time'] != '') {
                            
                            $actual_time_array[] = $v1['actual_time'];
                        }
                    }

                    // Calculate Total Projected Time
                    if(isset($projected_time_array) && $projected_time_array != '') {

                        $time_in_secs = array_map(function ($v) { return strtotime($v) - strtotime('00:00'); }, $projected_time_array);
                        $total_time = array_sum($time_in_secs);
                        $hours = floor($total_time / 3600);
                        $minutes = floor(($total_time % 3600) / 60);
                        $seconds = $total_time % 60;
                        
                        $total_projected_time = str_pad($hours, 2, '0', STR_PAD_LEFT)
                           . ":" . str_pad($minutes, 2, '0', STR_PAD_LEFT) 
                           . ":" . str_pad($seconds, 2, '0', STR_PAD_LEFT) . "\n";
                    }
                    else {
                        $total_projected_time = NULL;
                    }

                    // Calculate Total Projected Time

                    if(isset($actual_time_array) && $actual_time_array != '') {

                        $time_in_secs_1 = array_map(function ($v_1) { return strtotime($v_1) - strtotime('00:00'); }, $actual_time_array);
                        $total_time_1 = array_sum($time_in_secs_1);
                        $hours_1 = floor($total_time_1 / 3600);
                        $minutes_1 = floor(($total_time_1 % 3600) / 60);
                        $seconds_1 = $total_time_1 % 60;
                        
                        $total_actual_time = str_pad($hours_1, 2, '0', STR_PAD_LEFT)
                           . ":" . str_pad($minutes_1, 2, '0', STR_PAD_LEFT) 
                           . ":" . str_pad($seconds_1, 2, '0', STR_PAD_LEFT) . "\n";
                    }
                    else {
                        $total_actual_time = NULL;
                    }

                    \DB::statement("UPDATE `work_planning` SET `total_projected_time` = '$total_projected_time', `total_actual_time` = '$total_actual_time' WHERE id = $wp_id");
                }
            }
        }
    }
}