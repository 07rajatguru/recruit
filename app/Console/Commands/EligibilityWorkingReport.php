<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\UserOthersInfo;
use App\Bills;
use App\Eligibilityworking;

class EligibilityWorkingReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eligibility:working';

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
        $users = User::getAllUsers('recruiter');

        foreach ($users as $key => $value) {
            $user_data = UserOthersInfo::getUserOtherInfo($key);
            $user_salary = $user_data['fixed_salary'];

            $target = $user_salary * 3.5;
            $achieved = 0;

            $start_month = date('Y-m-d',strtotime("first day of this month"));
            $last_month = date('Y-m-d',strtotime("last day of this month"));

            // get user billing amount
            $user_bill_data = Bills::getPersonwiseReportData($key,$start_month,$last_month);
            //print_r($user_bill_data);exit;
            foreach ($user_bill_data as $key1 => $value1) {
                $achieved = $achieved + $value1['person_billing'];
            }
            // Check Eligibility
            if ($achieved == 0) {
                $eligibility = 'false';
            }
            else if ($achieved >= $target) {
                $eligibility = 'true';
            }
            else {
                $eligibility = 'false';
            }

            $month = date('m');
            $year = date('Y');

            // Add data in eligibility table
            $eligibility_data_id = Eligibilityworking::getCheckuserworkingreport($key,$month,$year);
            if (isset($eligibility_data_id) && $eligibility_data_id != '') {
                $eligible = Eligibilityworking::find($eligibility_data_id);
                $eligible->user_id = $key;
                $eligible->target = $target;
                $eligible->achieved = $achieved;
                $eligible->eligibility = $eligibility;
                $eligible->date = $start_month;
                $eligible->save();
            }
            else {
                $eligible = new Eligibilityworking();
                $eligible->user_id = $key;
                $eligible->target = $target;
                $eligible->achieved = $achieved;
                $eligible->eligibility = $eligibility;
                $eligible->date = $start_month;
                $eligible->save();
            }

        //print_r($eligibility_data_id);exit;
        }
    }
}
