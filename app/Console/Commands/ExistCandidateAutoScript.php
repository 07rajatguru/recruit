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
        //$candidate_res = CandidateBasicInfo::getCandidateDetails(25,0,'','');
        $candidate_res = CandidateBasicInfo::getCandidateDetails(2,0,'','');

        //print_r($candidate_res);exit;

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['app_url'] = $app_url;
        $input['company_name'] = 'Adler Talent Solution';
        $input['subject'] = 'Thanks for your application - '.$input['company_name'];

        //echo $input['subject'];exit;

        //print_r($input);exit;

        if(isset($candidate_res) && sizeof($candidate_res) > 0) {

            foreach ($candidate_res as $key => $value) {

                $input['candidate_name'] = $value['full_name'];
                /*$input['to'] = $value['email'];
                $input['cc'] = 'dhara@trajinfotech.com';*/
                /*$input['to'] = 'tarikapanjwani@gmail.com';
                $input['cc'] = 'saloni@trajinfotech.com';*/

                $input['to'] = 'saloni@trajinfotech.com';
                $input['cc'] = 'trajinfotech15@gmail.com';
                $candidate_id = $value['id'];

                //print_r($input);exit;

                \Mail::send('adminlte::emails.existCandidateAutoScriptMail', $input, function ($message) use($input)
                {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc'])->subject($input['subject']);
                });

                \DB::statement("UPDATE candidate_basicinfo SET autoscript_status = '1' where id = '$candidate_id';");

                echo $value['id']." - 1". "\n";
            }
        }
    }
}
