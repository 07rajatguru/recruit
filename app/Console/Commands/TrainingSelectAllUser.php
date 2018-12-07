<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\TrainingVisibleUser;

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
        $users = User::getAllUsers('recruiter');
        $user_count = sizeof($users);

        $training = TrainingVisibleUser::getUserIdCount();
        print_r($training);
    }
}
