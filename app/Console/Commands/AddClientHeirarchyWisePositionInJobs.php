<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ClientHeirarchy;
use App\JobOpen;


class AddClientHeirarchyWisePositionInJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clientheirarchy:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Add Client Heirarchy Wise Position In Jobs Module';

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
        $all_jobs = JobOpen::getAllJobsPostingTitle();

        if(isset($all_jobs) && sizeof($all_jobs) > 0) {

            foreach ($all_jobs as $key => $value) {

                if($value['level_id'] > 0) {

                    $job_id = $value['id'];

                    $get_position = ClientHeirarchy::getClientHeirarchyPositionById($value['level_id']);

                    if($get_position == 'Below AM') {

                        \DB::statement("UPDATE job_openings SET level_id = '1' where id=$job_id");

                    }

                    if($get_position == 'Above AM') {

                        \DB::statement("UPDATE job_openings SET level_id = '2' where id=$job_id");
                        
                    }
                }
            }
        }
    }
}