<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\UsersEmailPwd;

class AddUsersEmailPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:usersemailpwd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script for add Exists Users Email & Password to new table';

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
        $users = User::getAllUsersWithEmails();

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {

                $users_email_pwd = new UsersEmailPwd();
                $users_email_pwd->user_id = $key;
                $users_email_pwd->email = $value;
                $users_email_pwd->save();
            }
        }
    }
}