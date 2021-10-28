<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\UserOthersInfo;
use App\LeaveBalance;

class SeekLeaveBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seekleave:balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Set Seek Leave Balance to Zero in every year.';

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

                \DB::statement("UPDATE `leave_balance` SET `seek_leave_total` = 0.5, `seek_leave_taken` = 0.00, `seek_leave_remaining` = 0.5 WHERE `user_id` = '$key'");
            }
        }
    }
}