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

            $leave_data = LeaveBalance::getLeaveBalanceByUserId($key);

            if (isset($leave_data) && $leave_data != '') {

                $total_leave = $leave_data['leave_total'];
                $remaining_leave = $leave_data['leave_remaining'];

                $total_seek_leave = $leave_data['seek_leave_total'];
                $remaining_seek_leave = $leave_data['seek_leave_remaining'];

                $new_total_leave = $total_leave + 1.5;
                $new_remaining_leave = $remaining_leave + 1.5;

                $new_total_seek_leave = $total_seek_leave + 0.5;
                $new_remaining_seek_leave = $remaining_seek_leave + 0.5;

                \DB::statement("UPDATE `leave_balance` SET `leave_total` = '$new_total_leave', `leave_remaining` = '$new_remaining_leave',`seek_leave_total` = '$new_total_seek_leave', `seek_leave_remaining` = '$new_remaining_seek_leave' WHERE `user_id` = '$key'");
            }
            else {

                $user_data = UserOthersInfo::getUserOtherInfo($key);

                if (isset($user_data) && $user_data != '') {

                    $date_of_joining = $user_data['date_of_joining'];
                    $after_six_month = date('Y-m-d', strtotime("+6 month $date_of_joining"));
                    $current_date = date('Y-m-d');

                    if ($after_six_month <= $current_date) {

                        //Add User Leave Balance data
                        $leave_balance = new LeaveBalance();
                        $leave_balance->user_id = $key;
                        $leave_balance->leave_total = 1.5;
                        $leave_balance->leave_taken = 0;
                        $leave_balance->leave_remaining = 1.5;
                        $leave_balance->seek_leave_total = 0.5;
                        $leave_balance->seek_leave_taken = 0;
                        $leave_balance->seek_leave_remaining = 0.5;
                        $leave_balance->save();
                    }
                }
            }
        }
    }
}