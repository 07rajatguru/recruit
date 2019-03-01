<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserLeave;
use App\UsersLog;
use App\LeaveBalance;

class UserLeaveCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:leavecheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $yesterday = date('Y-m-d',strtotime('-1day'));
        // Get yesterdays application of leave
        $leave_data = UserLeave::getLeaveDataByFromDate($yesterday);
        foreach ($leave_data as $key => $value) {
            $user_id = $value['user_id'];
            $from_date = $value['from_date'];
            $status = $value['status'];
            // Check that user attendance
            $user_attendance = UsersLog::getUserAttendanceByIdDate($user_id,$from_date);
            if (isset($user_attendance) && sizeof($user_attendance)>0) {
                foreach ($user_attendance as $key => $value) {
                    $total = $value['total'];
                    // On full day leave
                    if ($total < "04:30") {
                        // If leave approved
                        if ($status == 1) {
                            // Get previous leave data of user
                            $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
                            if (isset($leave_balance) && $leave_balance != '') {
                                $leave_taken = $leave_balance->leave_taken;
                                $leave_remaining = $leave_balance->leave_remaining;

                                $new_leave = 1;

                                $new_leave_taken = $leave_taken + $new_leave;
                                $new_leave_remaining = $leave_remaining - $new_leave;

                                \DB::statement("UPDATE leave_balance SET leave_taken = '$new_leave_taken', leave_remaining = '$new_leave_remaining' where user_id = '$user_id'");
                            }
                            else {
                                print_r("Please Add Users Leave Balance Data.");
                            }
                        }
                        // If leave unapproved
                        else if ($status == 2) {
                            // Get previous leave data of user
                            $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
                            if (isset($leave_balance) && $leave_balance != '') {
                                $leave_taken = $leave_balance->leave_taken;
                                $leave_remaining = $leave_balance->leave_remaining;

                                $new_leave = 2;

                                $new_leave_taken = $leave_taken + $new_leave;
                                $new_leave_remaining = $leave_remaining - $new_leave;

                                \DB::statement("UPDATE leave_balance SET leave_taken = '$new_leave_taken', leave_remaining = '$new_leave_remaining' where user_id = '$user_id'");
                            }
                            else {
                                print_r("Please Add Users Leave Balance Data.");
                            }
                        }
                    }
                    // On half day leave
                    else if ($total >= "04:30" && $total < "09:00") {
                        // If leave approved
                        if ($status == 1) {
                            // Get previous leave data of user
                            $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
                            if (isset($leave_balance) && $leave_balance != '') {
                                $leave_taken = $leave_balance->leave_taken;
                                $leave_remaining = $leave_balance->leave_remaining;

                                $new_leave = 0.5;

                                $new_leave_taken = $leave_taken + $new_leave;
                                $new_leave_remaining = $leave_remaining - $new_leave;

                                \DB::statement("UPDATE leave_balance SET leave_taken = '$new_leave_taken', leave_remaining = '$new_leave_remaining' where user_id = '$user_id'");
                            }
                            else {
                                print_r("Please Add Users Leave Balance Data.");
                            }
                        }
                        // If leave unapproved
                        else if ($status == 2) {
                            // Get previous leave data of user
                            $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
                            if (isset($leave_balance) && $leave_balance != '') {
                                $leave_taken = $leave_balance->leave_taken;
                                $leave_remaining = $leave_balance->leave_remaining;

                                $new_leave = 1.5;

                                $new_leave_taken = $leave_taken + $new_leave;
                                $new_leave_remaining = $leave_remaining - $new_leave;

                                \DB::statement("UPDATE leave_balance SET leave_taken = '$new_leave_taken', leave_remaining = '$new_leave_remaining' where user_id = '$user_id'");
                            }
                            else {
                                print_r("Please Add Users Leave Balance Data.");
                            }
                        }
                    }
                }
            }
            else {
                // If leave approved
                if ($status == 1) {
                    // Get previous leave data of user
                    $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
                    if (isset($leave_balance) && $leave_balance != '') {
                        $leave_taken = $leave_balance->leave_taken;
                        $leave_remaining = $leave_balance->leave_remaining;

                        $new_leave = 1;

                        $new_leave_taken = $leave_taken + $new_leave;
                        $new_leave_remaining = $leave_remaining - $new_leave;

                        \DB::statement("UPDATE leave_balance SET leave_taken = '$new_leave_taken', leave_remaining = '$new_leave_remaining' where user_id = '$user_id'");
                    }
                    else {
                        print_r("Please Add Users Leave Balance Data.");
                    }
                }
                // If leave unapproved
                else if ($status == 2) {
                    // Get previous leave data of user
                    $leave_balance = LeaveBalance::getLeaveBalanceByUserId($user_id);
                    if (isset($leave_balance) && $leave_balance != '') {
                        $leave_taken = $leave_balance->leave_taken;
                        $leave_remaining = $leave_balance->leave_remaining;

                        $new_leave = 2;

                        $new_leave_taken = $leave_taken + $new_leave;
                        $new_leave_remaining = $leave_remaining - $new_leave;

                        \DB::statement("UPDATE leave_balance SET leave_taken = '$new_leave_taken', leave_remaining = '$new_leave_remaining' where user_id = '$user_id'");
                    }
                    else {
                        print_r("Please Add Users Leave Balance Data.");
                    }
                }
            }
        }
    }
}
