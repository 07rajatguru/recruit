<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Interview;
use App\CandidateUploadedResume;

class InterviewPriorEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'priorinterview:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send prior interview email notifications';

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
        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59", time() + 86400);

        $users = User::getAllUsers('recruiter');

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {
                
                $interviews = Interview::getAllInterviews(0,$key,$from_date,$to_date);

                $to_address_client_owner = array();
                $to_address_candidate_owner = array();
                $type_array = array();
                $file_path_array = array();
                $j = 0;

                if(isset($interviews) && sizeof($interviews) > 0) {

                    foreach ($interviews as $key1 => $value1) {

                        if(isset($value1) && $value1 != '') {
                               
                            $client_email = Interview::getClientOwnerEmail($value1['id']);
                            $client_owner_email = $client_email->clientowneremail;

                            if(isset($client_owner_email) && $client_owner_email != '') {
                                $to_address_client_owner[$j] = $client_owner_email;
                            }

                            $candidate_email = Interview::getCandidateOwnerEmail($value1['id']);
                            $candidate_owner_email = $candidate_email->candidateowneremail;

                            if(isset($candidate_owner_email) && $candidate_owner_email != '') {
                                $to_address_candidate_owner[$j] = $candidate_owner_email;
                            }

                            $type_array[$j] = $value1['interview_type'];

                            // Candidate Attachment
                            $attachment = CandidateUploadedResume::getCandidateAttachment($value1['candidate_id']);

                            if (isset($attachment) && $attachment != '') {
                                $file_path = public_path() . "/" . $attachment->file;
                            }
                            else {
                                $file_path = '';
                            }
                            $file_path_array[$j] = $file_path;

                            $j++;
                        }
                    }

                    $to_address = array_merge($to_address_client_owner,$to_address_candidate_owner);
                    $to_address = array_unique($to_address);

                    $to = implode(' ',$to_address);
                    $from_name = getenv('FROM_NAME');
                    $from_address = getenv('FROM_ADDRESS');
                    $app_url = getenv('APP_URL');

                    $input['from_name'] = $from_name;
                    $input['from_address'] = $from_address;
                    $input['to_address'] = $to_address;
                    $input['app_url'] = $app_url;

                    if(isset($interviews) && sizeof($interviews) > 0) {
                        $input['interview_details'] = $interviews;
                    }

                    $input['subject'] = "Today's Interviews";
                    $input['type_string'] = implode(",", $type_array);
                    $input['file_path'] = $file_path_array;

                    if(isset($interviews) && sizeof($interviews) > 0) {
                        
                        \Mail::send('adminlte::emails.interviewmultipleschedule', $input, function ($message) use($input) {
                            $message->from($input['from_address'], $input['from_name']);
                            $message->to($input['to_address'])->subject($input['subject']);

                            if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                                foreach ($input['file_path'] as $key => $value) {

                                    if(isset($value) && $value != '') {
                                        $message->attach($value);
                                    }
                                }
                            }
                        });
                    }
                }
            }
        }
    }
}