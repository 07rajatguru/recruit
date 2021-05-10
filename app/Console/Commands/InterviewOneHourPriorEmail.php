<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Interview;
use App\CandidateUploadedResume;

class InterviewOneHourPriorEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onehourpriorinterview:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send one hour prior interview email notifications';

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
        $curr_date_time = date('Y-m-d H:i:00', time() + 19800);

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $type_array = array($recruitment,$hr_advisory);

        $users = User::getAllUsers($type_array);

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {
                
                $interviews = Interview::getAllInterviews(0,$key,'','');

                $to_address_client_owner = array();
                $to_address_candidate_owner = array();
                $type_array = array();
                $file_path_array = array();
                $j = 0;

                if(isset($interviews) && sizeof($interviews) > 0) {

                    foreach ($interviews as $key1 => $value1) {

                        if(isset($value1) && $value1 != '') {

                            $get_interview_date = $value1['interview_date_actual'];

                            $one_hour_ago_interview_date = date("Y-m-d H:i:00",strtotime($get_interview_date . " - 1 hour"));
                               
                            if($one_hour_ago_interview_date == $curr_date_time) {

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

                                $interview_details[$j] = $value1;

                                $j++;
                            }
                        }
                    }

                    if(isset($interview_details) && sizeof($interview_details) > 0) {

                        $from_name = getenv('FROM_NAME');
                        $from_address = getenv('FROM_ADDRESS');
                        $app_url = getenv('APP_URL');

                        if(isset($to_address_client_owner) && $to_address_client_owner != '' && isset($to_address_candidate_owner) && $to_address_candidate_owner != '') {

                            $to_address = array_merge($to_address_client_owner,$to_address_candidate_owner);
                            $to_address = array_unique($to_address);
                        }
                        else {

                            $to_address = array();
                        }

                        $input['from_name'] = $from_name;
                        $input['from_address'] = $from_address;
                        $input['app_url'] = $app_url;
                        $input['to_address'] = $to_address;
                        $input['subject'] = 'Interview Reminder';
                        $input['type_string'] = implode(",", $type_array);
                        $input['file_path'] = $file_path_array;
                        $input['interview_details'] = $interview_details;

                        if(isset($to_address) && $to_address != '') {

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
}