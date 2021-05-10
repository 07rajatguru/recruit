<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\TrainingVisibleUser;
use App\Training;

class TrainingSelectAllUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'training:selectall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for training select all users.';

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
        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $type_array = array($recruitment,$hr_advisory);
        
        $users = User::getAllUsers($type_array);
        $user_count = sizeof($users);

        $training_id = Training::getAlltrainingIds();
        foreach ($training_id as $key) {
            $t_id = TrainingVisibleUser::getUserIdCount($key);
            if ($t_id == $user_count) {
                \DB::statement("UPDATE training SET select_all = '1' where id=$key");
                //print_r($t_id);exit;
            }
            else {
                \DB::statement("UPDATE training SET select_all = '0' where id=$key");
            }
        }
    }
}
