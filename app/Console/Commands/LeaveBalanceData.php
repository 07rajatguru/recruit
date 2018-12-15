<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\UserOthersInfo;
use App\LeaveBalance;

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
        $users = User::getAllUsers();

        foreach ($users as $key => $value) {
            $user_id = LeaveBalance::CheckUserID($key);
            if (isset($user_id) && $user_id > 0) {
                $leave_data = LeaveBalance::getLeaveBalanceByUserId($key);
                $total_leave = $leave_data['leave_total'];
                $remaining_leave = $leave_data['leave_remaining'];

                $new_total = $total_leave + 2;
                $new_remaining = $remaining_leave + 2;

                \DB::statement("UPDATE leave_balance SET leave_total = $new_total, leave_remaining = $new_remaining where user_id = $key");
                //print_r($new_remaining);exit;
            }
            else {
                $user_data = UserOthersInfo::getUserOtherInfo($key);
                if (isset($user_data) && $user_data != '') {
                    $date_of_joining = $user_data['date_of_joining'];
                    $after_six_month = date('Y-m-d', strtotime("+6 month $date_of_joining"));
                    $current_date = date('Y-m-d');

                    if ($after_six_month <= $current_date) {
                        $date1 = date_create($current_date);
                        $date2 = date_create($after_six_month);
                        $date_diff = date_diff($date1,$date2);
                        $year = $date_diff->format('%y');
                        $month = $date_diff->format('%m');

                        $month_convert = $year * 12;
                        $total_month = $month_convert + $month;
                        $total_leave = 2 * $total_month;
                        //print_r($total_leave);exit;
                        //Add User Leave Balance data
                        $leave_balance = new LeaveBalance();
                        $leave_balance->user_id = $key;
                        $leave_balance->leave_total = $total_leave;
                        $leave_balance->leave_taken = 0;
                        $leave_balance->leave_remaining = $total_leave;
                        $leave_balance->save();
                    }
                }
            }
        }
    }
}
