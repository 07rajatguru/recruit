<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WorkPlanning;
use App\User;
use App\Events\NotificationMail;

class PendingWorkPlanningReminderScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pendingworkplanning:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Sent Pending Work Planning Reminder';

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
        $from_date = date('Y-m-d',strtotime("-8 days"));
        $to_date = date("Y-m-d");

        $users = User::getAllUsers();

        foreach ($users as $key => $value) {

            $assigned_users = User::getAllUsersForRemarks($key,0);

            if(isset($assigned_users) && sizeof($assigned_users) > 0) {

                $user_ids = array();
                foreach ($assigned_users as $key1 => $value1) {
                        
                    if($key1 != '') {
                        $user_ids[] = $key1;
                    }
                }

                $work_planning_res = WorkPlanning::getPendingWorkPlanningsByDate($user_ids,$from_date,$to_date);

                if(isset($work_planning_res) && sizeof($work_planning_res) > 0) {

                    $module = "Pending Work Planning Reminder";
                    $sender_name = $key;
                    $to = User::getUserEmailById($key);
                    $subject = "Pending Work Planning Reminder";
                    $message = "";
                    $module_id = 0;
                    $cc = "";

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        }
    }
}