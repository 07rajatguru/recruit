<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\LeaveBalance;
use App\MonthwiseLeaveBalance;

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
        $superadmin_user_id = getenv('SUPERADMINUSERID');

        foreach ($users as $key => $value) {

            if($key == $superadmin_user_id) {

            }
            else {

                $user_data = User::getAllDetailsByUserID($key);

                if (isset($user_data) && $user_data != '') {

                    $joining_date = $user_data->joining_date;
                    $after_six_month = date('Y-m-d', strtotime("+6 month $joining_date"));
                    $current_date = date('Y-m-d');

                    if ($after_six_month <= $current_date) {

                        $leave_data = LeaveBalance::getLeaveBalanceByUserId($key);

                        if(isset($leave_balance) && $leave_balance != '') {

                            $leave_balance = LeaveBalance::find($leave_data->id);
                            $leave_balance->user_id = $key;
                            $leave_balance->leave_total = $leave_data->leave_total + 1.5;
                            $leave_balance->leave_remaining = $leave_data->leave_remaining + 1.5;
                            $leave_balance->seek_leave_total = $leave_data->seek_leave_total + 0.5;
                            $leave_balance->seek_leave_remaining = $leave_data->seek_leave_remaining + 0.5;
                            $leave_balance->save();
                        }
                        else {

                            //Add User Leave Balance data
                            $leave_balance = new LeaveBalance();
                            $leave_balance->user_id = $key;
                            $leave_balance->leave_total = 1.5;
                            $leave_balance->leave_taken = 0.0;
                            $leave_balance->leave_remaining = 1.5;
                            $leave_balance->seek_leave_total = 0.5;
                            $leave_balance->seek_leave_taken = 0.0;
                            $leave_balance->seek_leave_remaining = 0.5;
                            $leave_balance->save();
                        }

                        //Add User Leave Balance data Monthwise
                        $monthwise_leave_balance = new MonthwiseLeaveBalance();
                        $monthwise_leave_balance->user_id = $key;
                        $monthwise_leave_balance->pl_total = 1.5;
                        $monthwise_leave_balance->pl_taken = 0.0;
                        $monthwise_leave_balance->pl_remaining = 1.5;
                        $monthwise_leave_balance->sl_total = 0.5;
                        $monthwise_leave_balance->sl_taken = 0.0;
                        $monthwise_leave_balance->sl_remaining = 0.5;
                        $monthwise_leave_balance->month = date('m');
                        $monthwise_leave_balance->year = date('Y');
                        $monthwise_leave_balance->save();
                    }
                }
            }
        }
    }
}