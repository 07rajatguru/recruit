<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ProcessManual;
use App\ProcessVisibleUser;
use App\User;

class ProcessManualSelectAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:selectall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Process Manual select all users.';

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
        $user_count = sizeof($users);
        $process_id = ProcessManual::getAllprocessmanualIds();
        foreach ($process_id as $key) {
            $p_id = ProcessVisibleUser::getProcessUserIdCount($key);
        //print_r($p_id);exit;
            if ($p_id == $user_count) {
                \DB::statement("UPDATE process_manual SET select_all = '1' where id=$key");
            }
            else {
                \DB::statement("UPDATE process_manual SET select_all = '0' where id=$key");
            }
        }
    }
}
