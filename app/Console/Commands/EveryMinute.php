<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ToDos;
use App\User;
use App\EmailsNotifications;
use App\UserLeave;
use App\JobAssociateCandidates;
use App\Lead;
use App\Interview;
use App\Bills;
use App\ClientBasicinfo;
use App\LeaveDoc;
use App\CandidateBasicInfo;

class EveryMinute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:everyminute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $mail_res = \DB::table('emails_notification')
            ->select('emails_notification.*', 'emails_notification.id as id')
            ->where('status','=',0)
            ->limit(1)
            ->get();

        $mail = array();
        $i = 0;
        foreach ($mail_res as $key => $value) {
            $email_notification_id = $value->id;
            $mail[$i]['id'] = $value->id;
            $mail[$i]['module'] = $value->module;
            $mail[$i]['to'] = $value->to;
            $mail[$i]['cc'] = $value->cc;
            $mail[$i]['subject'] = $value->subject;
            $mail[$i]['message'] = $value->message;
            $mail[$i]['status'] = $value->status;
            $mail[$i]['module_id'] = $value->module_id;
            $mail[$i]['sender_name'] = $value->sender_name;
            $sent_date = date('Y-m-d');

            $status = 2;

            \DB::statement("UPDATE emails_notification SET sent_date = '$sent_date', status=$status where id = $email_notification_id");
            $i++;
        }

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;

        $input['mail'] = $mail;

        $status = 1;
        
        /*print_r($mail);
        exit;*/
        foreach ($mail as $key => $value) {

            $input['to'] = $value['to'];
            $input['cc'] = $value['cc'];
            $input['subject'] = $value['subject'];
            $input['message'] = $value['message'];
            $input['app_url'] = $app_url;
            $module_id = $value['module_id'];
            $sender_id = $value['sender_name'];

            if ($value['module'] == 'Job Open' || $value['module'] == 'Job Open to All') 
            {
                $to_array=array();
                $to_array=explode(",",$input['to']);

                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['to_array']=$to_array;
                $input['cc_array']=array_unique($cc_array);

                $id=$value['id'];

                $job = EmailsNotifications::getShowJobs($id);

                $input['job'] = $job;

                \Mail::send('adminlte::emails.emailNotification', $input, function ($job) use($input) {
                    $job->from($input['from_address'], $input['from_name']);
                    $job->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                        });  

                /*$jobopen=JobOpen::find($module_id);
                $input['jobopen_subject'] = $jobopen->subject;
                $input['description'] = $jobopen->description;

                $user_name = User::getUserNameByEmail($input['to']);
                $input['uname'] = $user_name;
                $input['jobopen_id'] = $module_id;

                \Mail::send('adminlte::emails.todomail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc'])->subject($input['subject']);
                });
*/
                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                
            } 
            else if ($value['module'] == 'Todos') 
            {

                // get todos subject and description

                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['cc_array']=$cc_array;

                $todos = ToDos::find($module_id);

                $input['todo_subject'] = $todos->subject;
                $input['description'] = $todos->description;

                $user_name = User::getUserNameByEmail($input['to']);

                $input['uname'] = $user_name;

                $input['todo_id'] = $module_id;

                \Mail::send('adminlte::emails.todomail', $input, function ($message) use ($input) {
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                        });              
               
                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }
            else if ($value['module'] == 'Leave') 
            {
                $cc_array=array();
                $cc_array=explode(",",$input['cc']); 

                $input['cc_array']=array_unique($cc_array);

                $leave = UserLeave::find($module_id);

                $leave_doc = LeaveDoc::getLeaveDocById($module_id);
                if (isset($leave_doc) && sizeof($leave_doc) > 0) {
                    $input['attachment'] = array();$j = 0;
                    foreach ($leave_doc as $key => $value) {
                        $input['attachment'][$j] = 'public/'.$value['fileName'];
                        $j++;
                    }
                }

                $input['leave_message'] = $leave->message;
                $input['leave_id'] = $module_id;

                $logged_in_user_id = $leave->user_id;

                $input['logged_in_user_nm'] = User::getUserNameById($logged_in_user_id);

                \Mail::send('adminlte::emails.leavemail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                    if (isset($input['attachment']) && sizeof($input['attachment'])>0) {
                        foreach ($input['attachment'] as $key => $value) {
                            $message->attach($value);
                        }
                    }
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'"); 

                /*\DB::statement("UPDATE user_leave SET `status`='$status' where `id` = '$module_id'"); */
            }
            else if ($value['module'] == 'Daily Report'){

                $to_array=explode(",",$input['to']);
                $cc_array=explode(",",$input['cc']);

                $associate_response = JobAssociateCandidates::getDailyReportAssociate($sender_id,NULL);
                //print_r($associate_response);exit;
                $associate_daily = $associate_response['associate_data'];
                $associate_count = $associate_response['cvs_cnt'];

                $lead_count = Lead::getDailyReportLeadCount($sender_id,NULL);

                $interview_daily = Interview::getDailyReportInterview($sender_id,NULL);
                $user_name = User::getUserNameById($sender_id);

                $input['value'] = $user_name;
                $input['associate_daily'] = $associate_daily;
                $input['associate_count'] = $associate_count;
                $input['lead_count'] = $lead_count;
                $input['interview_daily'] = $interview_daily;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.dailyReport', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Daily Activity Report - ' . $input['value'] . ' - ' . date("d-m-Y"));
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'"); 
            }
            else if ($value['module'] == 'Weekly Report') {
                $to_array=explode(",",$input['to']);
                $cc_array=explode(",",$input['cc']);

                $associate_weekly_response = JobAssociateCandidates::getWeeklyReportAssociate($sender_id,NULL,NULL);

                $associate_weekly = $associate_weekly_response['associate_data'];
                $associate_count = $associate_weekly_response['cvs_cnt'];

                $interview_weekly_response = Interview::getWeeklyReportInterview($sender_id,NULL,NULL);
                $interview_weekly = $interview_weekly_response['interview_data'];
                $interview_count = $interview_weekly_response['interview_cnt'];

                $lead_count = Lead::getWeeklyReportLeadCount($sender_id,NULL,NULL);
                $user_name = User::getUserNameById($sender_id);

                $input['value'] = $user_name;
                $input['associate_weekly'] = $associate_weekly;
                $input['associate_count'] = $associate_count;
                $input['interview_weekly'] = $interview_weekly;
                $input['interview_count'] = $interview_count;
                $input['lead_count'] = $lead_count;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.WeeklyReport', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Weekly Activity Report -'.$input['value']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Lead') {
                
                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['cc_array']=$cc_array;

                $lead_details = Lead::getLeadDetailsById($value['module_id']);
                //print_r($lead_details);exit;
                $input['lead_details'] = $lead_details;

                \Mail::send('adminlte::emails.leadaddemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Forecasting') {
                
                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['cc_array']=$cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);
                //print_r($bills_details);exit;
                $input['bills_details'] = $bills_details;

                \Mail::send('adminlte::emails.billsemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Recovery') {
                
                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['cc_array']=$cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);
                //print_r($bills_details);exit;
                $input['bills_details'] = $bills_details;

                \Mail::send('adminlte::emails.billsemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Cancel Forecasting') {
                
                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['cc_array']=$cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);
                //print_r($bills_details);exit;
                $input['bills_details'] = $bills_details;

                \Mail::send('adminlte::emails.billsemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Cancel Recovery') {
                
                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['cc_array']=$cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);
                //print_r($bills_details);exit;
                $input['bills_details'] = $bills_details;

                \Mail::send('adminlte::emails.billsemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Relive Forecasting') {
                
                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['cc_array']=$cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);
                //print_r($bills_details);exit;
                $input['bills_details'] = $bills_details;

                \Mail::send('adminlte::emails.billsemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Relive Recovery') {
                
                $cc_array=array();
                $cc_array=explode(",",$input['cc']);

                $input['cc_array']=$cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);
                //print_r($bills_details);exit;
                $input['bills_details'] = $bills_details;

                \Mail::send('adminlte::emails.billsemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Training Material') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['module_id'] = $value['module_id'];
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;

                \Mail::send('adminlte::emails.training', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Process Manual') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['module_id'] = $value['module_id'];
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;

                \Mail::send('adminlte::emails.processmanual', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Client') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $client = ClientBasicinfo::getClientDetailsById($module_id);

                $input['module_id'] = $value['module_id'];
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
                $input['client'] = $client;

                \Mail::send('adminlte::emails.clientaddmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Leave reply approved/unapproved
            else if ($value['module'] == 'Leave Reply') {
                $cc_array=array();
                $cc_array=explode(",",$input['cc']); 

                $input['cc_array']=array_unique($cc_array);

                \Mail::send('adminlte::emails.leavemail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'"); 
            }

            // Mail for Joining Confirmation of recovery
            else if ($value['module'] == 'Joining Confirmation'){

                $join_mail = Bills::getJoinConfirmationMail($module_id);

                $input['join_mail'] = $join_mail;

                \Mail::send('adminlte::emails.joinconfirmationmail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Invoice gererate of recovery
            else if ($value['module'] == 'Invoice Generate'){

                $join_mail = Bills::getJoinConfirmationMail($module_id);

                $input['join_mail'] = $join_mail;

                $input['attachment'] = public_path() . '/uploads/bills/'.$module_id.'/'.$module_id.'_invoice.xls';
                
                \Mail::send('adminlte::emails.invoicegenerate', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc'])->subject($input['subject']);
                    $message->attach($input['attachment']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Passive Client Listing
            else if ($value['module'] == 'Passive Client List'){

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                /*$user_name = User::getUserNameById($sender_id);
                $input['value'] = $user_name;*/

                $client_res = ClientBasicinfo::getPassiveClients();

                //print_r($client_res);exit;

                $clients_count = sizeof($client_res);
                
                $input['client_res'] = $client_res;
                $input['clients_count'] = $clients_count;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.PassiveClients', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Passive Client Listing');
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Client Bulk Email') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];
                $input['bulk_message'] = $value['message'];

                \Mail::send('adminlte::emails.clientbulkmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Applicant Candidate') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];
                $candidate_details = CandidateBasicInfo::getCandidateDetailsById($input['module_id']);

                $input['candidate_details'] = $candidate_details;
                $input['resume'] = public_path() . $candidate_details['org_resume_path'];

                \Mail::send('adminlte::emails.applicantcandidatemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->subject($input['subject']);
                    $message->attach($input['resume']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Expected Passive Client Listing in next week
            else if ($value['module'] == 'Expected Passive Client'){

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);
                $client_ids = $value['module_id'];
                print_r($client_ids);exit;

                /*$user_name = User::getUserNameById($sender_id);
                $input['value'] = $user_name;*/

                $client_res = ClientBasicinfo::getPassiveClients();

                //print_r($client_res);exit;

                $clients_count = sizeof($client_res);
                
                $input['client_res'] = $client_res;
                $input['clients_count'] = $clients_count;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                // \Mail::send('adminlte::emails.PassiveClients', $input, function ($message) use($input) {
                //     $message->from($input['from_address'], $input['from_name']);
                //     $message->to($input['to_array'])->cc($input['cc_array'])->subject('Passive Client Listing');
                // });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }
        }
    }
}
