<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CandidateBasicInfo;

class ExistCandidateAutoScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'existcandidate:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send Email Notifications to Exist Candidates.';

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
        $candidate_res = CandidateBasicInfo::getCandidateDetails(1,0);

        //print_r($candidate_res);exit;

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['app_url'] = $app_url;
        $input['subject'] = 'Thanks for your application - Adler Talent Solution';

        if(isset($candidate_res) && sizeof($candidate_res) > 0) {

            foreach ($candidate_res as $key => $value) {

                $candidate_id = $value['id'];

                \DB::statement("UPDATE candidate_basicinfo SET autoscript_status = '2' where id = $candidate_id");

                if(isset($value['email']) && $value['email'] != '') {

                    $input['candidate_name'] = $value['full_name'];
                    $input['owner_email'] = 'info@adlertalent.com';
                    $input['to'] = trim($value['email']);

                    \Mail::send('adminlte::emails.existCandidateAutoScriptMail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE candidate_basicinfo SET autoscript_status = '1' where id = '$candidate_id';");

                    echo $candidate_id . " - 1". "\n";
                }
                else {
                    
                    \DB::statement("UPDATE candidate_basicinfo SET autoscript_status = '1' where id = '$candidate_id';");

                    echo $candidate_id . " - 0". "\n";
                }
            }
        }
    }
}
