<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\LeaveBalance;
use App\MonthwiseLeaveBalance;
use App\Events\NotificationMail;

class LeaveBalanceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update user leave balance in every month';

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
        $users = User::getAllUsersExpectSuperAdmin();

        foreach ($users as $key => $value) {

            $leave_data = LeaveBalance::getLeaveBalanceByUserId($key);

            //Add User Leave Balance data Monthwise
            $month = date('m');
            $year = date('Y');
            
            if(isset($leave_data) && $leave_data != '') {

                $leave_balance = LeaveBalance::find($leave_data->id);
                $leave_balance->leave_total = $leave_data->leave_total + 1.5;
                $leave_balance->leave_remaining = $leave_data->leave_remaining + 1.5;
                /*$leave_balance->seek_leave_total = $leave_data->seek_leave_total + 0.5;
                $leave_balance->seek_leave_remaining = $leave_data->seek_leave_remaining + 0.5;*/
                $leave_balance->save();
                
                $monthwise_leave_balance = new MonthwiseLeaveBalance();
                $monthwise_leave_balance->user_id = $key;
                $monthwise_leave_balance->pl_total = 1.5;
                $monthwise_leave_balance->pl_taken = 0.0;
                $monthwise_leave_balance->pl_remaining = 1.5;
                /*$monthwise_leave_balance->sl_total = 0.5;
                $monthwise_leave_balance->sl_taken = 0.0;
                $monthwise_leave_balance->sl_remaining = 0.5;*/
                $monthwise_leave_balance->month = $month;
                $monthwise_leave_balance->year = $year;
                $monthwise_leave_balance->save();
            }
            else {

                $user_data = User::getAllDetailsByUserID($key);

                if (isset($user_data) && $user_data != '') {

                    $joining_date = $user_data->joining_date;
                    $after_six_month = date('Y-m-d', strtotime("+6 month $joining_date"));
                    $current_date = date('Y-m-d');

                    if ($after_six_month <= $current_date) {

                        //Add User Leave Balance data
                        $leave_balance = new LeaveBalance();
                        $leave_balance->user_id = $key;
                        $leave_balance->leave_total = 1.5;
                        $leave_balance->leave_taken = 0.0;
                        $leave_balance->leave_remaining = 1.5;
                        /*$leave_balance->seek_leave_total = 0.5;
                        $leave_balance->seek_leave_taken = 0.0;
                        $leave_balance->seek_leave_remaining = 0.5;*/
                        $leave_balance->save();

                        //Add User Leave Balance data Monthwise
                        $monthwise_leave_balance = new MonthwiseLeaveBalance();
                        $monthwise_leave_balance->user_id = $key;
                        $monthwise_leave_balance->pl_total = 1.5;
                        $monthwise_leave_balance->pl_taken = 0.0;
                        $monthwise_leave_balance->pl_remaining = 1.5;
                        /*$monthwise_leave_balance->sl_total = 0.5;
                        $monthwise_leave_balance->sl_taken = 0.0;
                        $monthwise_leave_balance->sl_remaining = 0.5;*/
                        $monthwise_leave_balance->month = $month;
                        $monthwise_leave_balance->year = $year;
                        $monthwise_leave_balance->save();
                    }
                }
            }
        }

        // Send Email Notification of Active Users Leave Balance

        //Get Superadmin Email
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        $module = "Leave Balance";
        $subject = "Users Leave Balance";
        $message = "";
        $to = $superadminemail;
        $cc = "";
        $module_id = 0;
        $sender_name = $superadminuserid;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
    }
}