<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class GenerateEmployeeID extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Generate Employee ID from Joining Date.';

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
        $users = User::getAllUsersByJoiningDate();

        if(isset($users) && $users != '') {

            $employee_id_increment = '1';

            foreach ($users as $key => $value) {
                
                if($value['date_of_joining'] != '') {

                    $user_id = $value['id'];
                    $join_year = date('y',strtotime($value['date_of_joining']));
                    $next_year =  $join_year + 1;

                    if($employee_id_increment < '10') {

                        $employee_id = 'ATS' . $join_year . $next_year . '0' . $employee_id_increment;
                    }
                    else {
                        $employee_id = 'ATS' . $join_year . $next_year . $employee_id_increment;
                    }

                    \DB::statement("UPDATE users_otherinfo SET `employee_id`='$employee_id' where `user_id` = '$user_id'");

                    \DB::statement("UPDATE users_otherinfo SET `employee_id_increment`='$employee_id_increment' where `user_id` = '$user_id'");

                    $employee_id_increment = $employee_id_increment + 1;

                    echo $user_id . " - " . $employee_id . "\n";
                }
            }
        }
    }
}
