<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\UserOthersInfo;

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
    protected $description = 'Command for update leave balance data description';

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
        $users = User::getAllUsersEmails();

        foreach ($users as $key => $value) {
            
            $user_data = UserOthersInfo::getUserOtherInfo($key);
            $date_of_joining = $user_data['date_of_joining'];
            $after_six_month = date('Y-m-d', strtotime("+6 month $date_of_joining"));
            $current_date = date('Y-m-d');

            if ($after_six_month <= $current_date) {
                print_r($after_six_month);exit;
            }
        }
            print_r($current_date);exit;
    }
}
