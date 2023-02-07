<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\WorkPlanning;
use App\Date;

class MonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for add all sundays entry in Work Planning Table';

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
        /*$recruitment = getenv('RECRUITMENT');
        $users_all = User::getAllUsersEmails($recruitment,'Yes');

        foreach ($users_all as $k1 => $v1) {

            $user_name = User::getUserNameById($k1);

            $module = "Monthly Report";
            $subject = 'Monthly Activity Report - ' . $user_name . ' - ' . date("F",strtotime("last month"))." ".date("Y");
            $message = "";
            $to = $v1;
            //$cc = 'rajlalwani@adlertalent.com';
            $cc = 'info@adlertalent.com';

            $module_id = 0;
            $sender_name = $k1;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }*/

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $operations = getenv('OPERATIONS');
        $type_array = array($recruitment,$hr_advisory,$operations);

        $users = User::getAllUsersExpectSuperAdmin($type_array);

        if(isset($users) && sizeof($users) > 0) {

            $month = date('m');
            $year = date('Y');

            // Get All Sundays dates of current month
            $date = "$year-$month-01";
            $first_day = date('N',strtotime($date));
            $first_day = 7 - $first_day + 1;
            $last_day =  date('t',strtotime($date));
            $sundays = array();

            for($i = $first_day; $i <= $last_day; $i = $i+7 ) {

                if($i < 10) {
                    $i = "0$i";
                }
                $sundays[] = $i;
            }

            if(isset($sundays) && sizeof($sundays) > 0) {

                foreach ($sundays as $k => $v) {

                    foreach ($users as $key => $value) {

                        $sunday_date = $year."-".$month."-".$v;

                        //If Sunday Entry already exists then not add
                        $get_work_planning_res = WorkPlanning::getWorkPlanningByAddedDateAndUserID($sunday_date,$key);

                        if(isset($get_work_planning_res) && $get_work_planning_res != '') {
                        }
                        else {
                            $work_planning = new WorkPlanning();
                            $work_planning->added_date = $sunday_date;
                            $work_planning->added_by = $key;
                            $work_planning->save();
                        }
                    }
                }
            }

            // Get All Saturday dates of current month
            // $date = "$year-$month-01";
            // $first_day = date('N',strtotime($date));
            // $first_day = 6 - $first_day + 1;
            // $last_day =  date('t',strtotime($date));
            // $saturdays = array();

            // for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
            //     $saturdays[] = $i;
            // }

            // // Get only Third Saturday's date
            // $saturday_date = $year."-".$month."-".$saturdays[2];
            $third_saturday = Date::getThirdSaturdayOfMonth($month,$year);
            $saturday_date = $third_saturday['full_date'];

            // Add Third Saturday's Entry
            foreach ($users as $key1 => $value1) {
                
                //If Saturday Entry already exists then not add
                $get_work_planning_res = WorkPlanning::getWorkPlanningByAddedDateAndUserID($saturday_date,$key1);

                if(isset($get_work_planning_res) && $get_work_planning_res != '') {
                }
                else {
                    $work_planning = new WorkPlanning();
                    $work_planning->added_date = $saturday_date;
                    $work_planning->attendance = 'TS';
                    $work_planning->added_by = $key1;
                    $work_planning->save();
                }
            }
        }
    }
}