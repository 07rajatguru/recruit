<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Holidays;
use App\Events\NotificationMail;
use App\WorkPlanning;

class DailyWorkPlanningSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workplanning:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send daily work planning summary for all reports to employees';

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
        date_default_timezone_set('UTC');
        $date = date('Y-m-d');
        $fixed_date = Holidays::getFixedLeaveDate();

        // Get All Saturday dates of current month
        // $s_date = date("Y-m-d", strtotime("first day of this month"));
        // $first_day = date('N',strtotime($s_date));
        // $first_day = 6 - $first_day + 1;
        // $last_day =  date('t',strtotime($s_date));
        // $saturdays = array();
        // for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
        //     $saturdays[] = $i;
        // }

        // // Get Saturday Date
        // $saturday_date = date('Y-m')."-".$saturdays[2];
        $month = date('m');
        $year = date('Y');
        $third_saturday = Date::getThirdSaturdayOfMonth($month,$year);
        $saturday_date = $third_saturday['full_date'];
        $dayOfWeek = date("l", strtotime($date));
        if ($dayOfWeek == 'Sunday' || $saturday_date == $date || (in_array($date, $fixed_date))) {
        } else {
            $users_data = User::getAllUsersDataWithReportsTo();
            if (isset($users_data) && sizeof($users_data)>0) {
                foreach ($users_data as $key => $value) {
                    $to_array = array();$cc_array = array();$module_ids = array();
                    if (isset($value) && sizeof($value) > 0) {
                        foreach ($value as $key_u => $value_u) {
                            $today_work_planning = WorkPlanning::getWorkPlanningsByDateAndUserId($value_u['id'],$date);
                            if(isset($today_work_planning) && $today_work_planning != '') {
                                $module_ids[] = $value_u['id'];
                            }
                        }
                    }

                    if (isset($module_ids) && sizeof($module_ids) > 0) {
                        $user_name = User::getUserNameById($key);
                        $user_email = User::getUserEmailById($key);

                        $to_array[] = $user_email;
                        $cc_array[] = 'info@adlertalent.com';

                        $module = "Daily Work Planning Summary";
                        $subject = 'Daily Work Planning Summary - ' . $user_name . ' - ' . date("d-m-Y");
                        $message = "";
                        $to_array = array_filter($to_array);
                        $to = implode(",",$to_array);

                        $cc_array = array_filter($cc_array);
                        $cc = implode(",",$cc_array);
                        $module_id = implode(",", $module_ids);
                        $sender_name = $key;

                        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                    }
                }
            }
        }
    }
}
