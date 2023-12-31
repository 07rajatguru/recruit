<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CandidateBasicInfo;

class CandidateAutoScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candidate:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send Email Notifications to Candidate which add today.';

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
        $from_date = date('Y-m-d 00:00:00');
        $to_date = date('Y-m-d 23:59:59');

        $candidate_res = CandidateBasicInfo::getCandidateDetails(0,0,$from_date,$to_date);

        //print_r($candidate_res);exit;

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['app_url'] = $app_url;
        $input['company_name'] = 'Adler Talent Solution';
        $input['subject'] = 'Thanks for your application - '.$input['company_name'];

        if(isset($candidate_res) && sizeof($candidate_res) > 0) {

            foreach ($candidate_res as $key => $value) {

                $candidate_id = $value['id'];

                \DB::statement("UPDATE candidate_basicinfo SET autoscript_status = '2' where id = $candidate_id");

                $input['candidate_name'] = $value['full_name'];
                $input['owner_email'] = $value['owner_email'];
                $input['to'] = $value['email'];

                \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->subject($input['subject']);
                });

                \DB::statement("UPDATE candidate_basicinfo SET autoscript_status = '1' where id = '$candidate_id';");

                echo $candidate_id . " - 1". "\n";
            }
        }
    }
}
