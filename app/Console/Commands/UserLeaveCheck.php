<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserLeave;
use App\UsersLog;

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
        $yesterday = date('Y-m-d'/*,strtotime('-1day')*/);

        $leave_data = UserLeave::getLeaveDataByFromDate($yesterday);
        foreach ($leave_data as $key => $value) {
            $user_id = $value['user_id'];
            $from_date = $value['from_date'];
            $status = $value['status'];

            $user_attendance = UsersLog::getUserAttendanceByIdDate($user_id,$from_date);
            if (isset($user_attendance) && sizeof($user_attendance)>0) {
                if ($status == 1) {
                    print_r("App");exit;
                }
                else if ($status == 2) {
                    print_r("un");exit;
                }
            }
            else {
                print_r("expression");exit;
            }
        }
    }
}
