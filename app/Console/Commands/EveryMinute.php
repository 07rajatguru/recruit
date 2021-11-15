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
use App\BillsDoc;
use App\Role;
use App\UserBenchMark;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\ClientTimeline;
use App\UsersEmailPwd;
use App\Date;
use App\CandidateUploadedResume;
use App\EmailTemplate;
use App\Contactsphere;
use App\JobOpen;
use App\TicketsDiscussion;
use App\TicketsDiscussionDoc;
use App\TicketDiscussionPost;
use App\TicketsDiscussionPostDoc;
use App\WorkPlanning;
use App\WorkPlanningList;

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
        ->where('status','=',0)->limit(1)->get();

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

        foreach ($mail as $key => $value) {

            $input['to'] = $value['to'];
            $input['cc'] = $value['cc'];
            $input['subject'] = $value['subject'];
            $input['message'] = $value['message'];
            $input['app_url'] = $app_url;
            $module_id = $value['module_id'];
            $sender_id = $value['sender_name'];

            if ($value['module'] == 'Job Open' || $value['module'] == 'Job Open to All') {

                $to_array = array();
                $to_array = explode(",",$input['to']);

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = array_unique($cc_array);

                $id = $value['id'];

                $job = EmailsNotifications::getShowJobs($id);

                $input['job'] = $job;

                \Mail::send('adminlte::emails.emailNotification', $input, function ($job) use($input) {
                    $job->from($input['from_address'], $input['from_name']);
                    $job->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });  

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            } 

            else if ($value['module'] == 'Todos') {

                // get todos subject and description

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = $cc_array;

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

            else if ($value['module'] == 'Leave') {

                // Get Sender name details
                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;
                $input['owner_email'] = $user_details->email;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

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

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([
                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.leavemail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc'])->bcc($input['owner_email'])->subject($input['subject']);

                    if (isset($input['attachment']) && sizeof($input['attachment']) > 0) {
                        
                        foreach ($input['attachment'] as $key => $value) {
                            $message->attach($value);
                        }
                    }
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'"); 
            }

            else if ($value['module'] == 'Daily Report') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $associate_response = JobAssociateCandidates::getDailyReportAssociate($sender_id,NULL);
                $associate_daily = $associate_response['associate_data'];
                $associate_count = $associate_response['cvs_cnt'];
                   
                // Get Leads with count

                $leads = Lead::getDailyReportLeads($sender_id,NULL);
                $leads_daily = $leads['leads_data'];
               
                $leads_count = Lead::getDailyReportLeadCount($sender_id,NULL);

                $interview_daily = Interview::getDailyReportInterview($sender_id,NULL);
                   
                $user_details = User::getAllDetailsByUserID($sender_id);

                $input['value'] = $user_details->name;

                $input['user_details'] = $user_details;
                $input['associate_daily'] = $associate_daily;
                $input['associate_count'] = $associate_count;

                $input['leads_daily'] = $leads_daily;
                $input['leads_count'] = $leads_count;
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

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $associate_weekly_response = JobAssociateCandidates::getWeeklyReportAssociate($sender_id,NULL,NULL);

                $associate_weekly = $associate_weekly_response['associate_data'];
                $associate_count = $associate_weekly_response['cvs_cnt'];

                $interview_weekly_response = Interview::getWeeklyReportInterview($sender_id,NULL,NULL);
                $interview_weekly = $interview_weekly_response['interview_data'];
                $interview_count = $interview_weekly_response['interview_cnt'];

                // Get Leads with count
                $leads = Lead::getWeeklyReportLeads($sender_id,NULL,NULL);
                $leads_weekly = $leads['leads_data'];
                $leads_count = Lead::getWeeklyReportLeadCount($sender_id,NULL,NULL);

                $user_details = User::getAllDetailsByUserID($sender_id);

                $input['value'] = $user_details->name;
                $input['user_details'] = $user_details;
                $input['associate_weekly'] = $associate_weekly;
                $input['associate_count'] = $associate_count;
                $input['interview_weekly'] = $interview_weekly;
                $input['interview_count'] = $interview_count;
                $input['leads_weekly'] = $leads_weekly;
                $input['leads_count'] = $leads_count;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.WeeklyReport', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Weekly Activity Report -'.$input['value']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Lead' || $value['module'] == 'Cancel Lead') {
                
                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = $cc_array;

                $lead_details = Lead::getLeadDetailsById($value['module_id']);
                
                $input['lead_details'] = $lead_details;

                \Mail::send('adminlte::emails.leadaddemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Forecasting' || $value['module'] == 'Recovery') {
                
                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = $cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);
                
                $input['bills_details'] = $bills_details;

                $bill_docs = BillsDoc::getBillDocs($value['module_id']);

                $input['bill_docs'] = $bill_docs;

                \Mail::send('adminlte::emails.billsemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);

                    if (isset($input['bill_docs']) && sizeof($input['bill_docs']) > 0) {

                        foreach ($input['bill_docs'] as $key => $value) {

                            if(isset($value['file']) && $value['file'] != '') {
                                $message->attach($value['file']);
                            }
                        }
                    }
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Cancel Forecasting' || $value['module'] == 'Cancel Recovery' || $value['module'] == 'Relive Forecasting' || $value['module'] == 'Relive Recovery') {
                
                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = $cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);

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

            else if ($value['module'] == 'Client' || $value['module'] == 'Forbid Client' || $value['module'] == 'Client Account Manager' || $value['module'] == 'Client Delete') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $client = ClientBasicinfo::getClientDetailsById($module_id);

                $input['module'] = $value['module'];
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

            else if ($value['module'] == 'List of Clients transferred') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $client_ids_array = explode(",",$module_id);
                $client_info = array();
                $i=0;

                foreach($client_ids_array as $key => $value) {

                    $client = ClientBasicinfo::getClientDetailsById($value);
                    $client_history = ClientTimeline::getTimelineDetailsByClientId($value);

                    if(isset($client_history[1]['user_id']) && $client_history[1]['user_id'] >= '0') {

                        $client_info[$i]['transferred_from'] = $client_history[1]['user_name'];
                    }
                    else {

                        $client_info[$i]['transferred_from'] = $client_history[0]['user_name'];
                    }
                    
                    if($client['am_name'] == '') {
                        $client_info[$i]['transferred_to'] = 'Yet to Assign';
                    }
                    else {
                        $client_info[$i]['transferred_to'] = $client['am_name'];
                    }
                    
                    $client_info[$i]['name'] = $client['name'];
                    $client_info[$i]['coordinator_name'] = $client['coordinator_name'];
                    $client_info[$i]['billing_city'] = $client['billing_city'];
                    
                    $i++;
                }

                $input['client_info'] = $client_info;
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;

                \Mail::send('adminlte::emails.clientmultipleaccountmanager', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Leave reply approved/unapproved
            else if ($value['module'] == 'Leave Reply') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']); 

                $input['cc_array'] = array_unique($cc_array);

                // Get Sender name details

                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                $leave = UserLeave::find($module_id);
                $input['leave_message'] = $leave->reply_message;
                $input['remarks'] = $leave->remarks;

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([
                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }


                \Mail::send('adminlte::emails.leavereply', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'"); 
            }

            // Mail for Joining Confirmation of recovery
            else if ($value['module'] == 'Joining Confirmation') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = array_unique($cc_array);

                $join_mail = Bills::getJoinConfirmationMail($module_id);

                $input['join_mail'] = $join_mail;

                \Mail::send('adminlte::emails.joinconfirmationmail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Invoice gererate of recovery
            else if ($value['module'] == 'Invoice Generate') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = array_unique($cc_array);

                $join_mail = Bills::getJoinConfirmationMail($module_id);

                $input['join_mail'] = $join_mail;

                $bill_invoice = BillsDoc::getBillInvoice($module_id,'Invoice');

                $input['xls_attachment'] = public_path() . "/" . $bill_invoice['file'];
                //$input['pdf_attachment'] = public_path() . '/uploads/bills/'.$module_id.'/'.$module_id.'_invoice.pdf';
                
                \Mail::send('adminlte::emails.invoicegenerate', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                    $message->attach($input['xls_attachment']);
                    //$message->attach($input['pdf_attachment']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Passive Client Listing
            else if ($value['module'] == 'Passive Client List') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $user_id = $value['module_id'];

                $client_res = ClientBasicinfo::getPassiveClients($user_id);
                $clients_count = sizeof($client_res);
                
                $input['client_res'] = $client_res;
                $input['clients_count'] = $clients_count;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.PassiveClients', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
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

                $client = ClientBasicinfo::getClientDetailsById($module_id);

                $input['owner_email'] = $client['am_email'];

                $user_details = User::getAllDetailsByUserID($value['sender_name']);

                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([
                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.clientbulkmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->bcc($input['owner_email'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
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
            else if ($value['module'] == 'Expected Passive Client') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);
                $client_ids = $value['module_id'];
                
                $client_res = ClientBasicinfo::getExpectedPassiveClients($client_ids);

                $clients_count = sizeof($client_res);
                
                $input['client_res'] = $client_res;
                $input['clients_count'] = $clients_count;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.PassiveClients', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Monthly Report') {

                $recruitment = getenv('RECRUITMENT');
                $hr_advisory = getenv('HRADVISORY');
                $type_array = array($recruitment,$hr_advisory);

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $superAdminUserID = getenv('SUPERADMINUSERID');
                $managerUserID = getenv('MANAGERUSERID');

                $access_roles_id = array($superAdminUserID,$managerUserID);

                if(in_array($value['sender_name'],$access_roles_id)) {
                    
                    $users_array = User::getAllUsersExpectSuperAdmin($type_array);

                    $users = array();

                    if(isset($users_array) && sizeof($users_array) > 0) {

                        foreach ($users_array as $k1 => $v1) {
                                       
                            $user_details = User::getAllDetailsByUserID($k1);

                            if($user_details->type == '2') {
                                if($user_details->hr_adv_recruitemnt == 'Yes') {
                                    $users[$k1] = $v1;
                                }
                            }
                            else {
                                $users[$k1] = $v1;
                            }    
                        }
                    }
                }
                else {
                    $users = User::getAssignedUsers($value['sender_name']);
                }

                $response = array();

                // set 0 value for all users
                foreach ($users as $k => $v) {

                    $response[$k]['cvs'] = 0;
                    $response[$k]['interviews'] = 0;
                    $response[$k]['lead_count'] = 0;
                    $response[$k]['leads_data'] = 0;
                    $response[$k]['uname'] = $users[$k];
                }

                $month = date('m',strtotime('last month'));

                if($month == 12) {
                    $year = date('Y',strtotime('last year'));
                }
                else {
                    $year = date('Y');
                }

                $associate_response = JobAssociateCandidates::getUserWiseAssociatedCVS($users,$month,$year);

                foreach ($associate_response as $k => $v) {
                    $response[$k]['cvs'] = $v;
                }

                $interview_count = Interview::getUserWiseMonthlyReportInterview($users,$month,$year);

                if(sizeof($interview_count) > 0) {
                    foreach ($interview_count as $k => $v) {
                        $response[$k]['interviews'] = $v;
                    }
                }

                $lead_count = Lead::getUserWiseMonthlyReportLeadCount($users,$month,$year);

                if(isset($lead_count) && sizeof($lead_count) > 0) {
                    foreach ($lead_count as $k => $v) {
                        $response[$k]['lead_count'] = $v;
                    }
                }

                $leads_details = Lead::getUserWiseMonthlyReportLeads($users,$month,$year);

                if(isset($leads_details) && sizeof($leads_details) > 0) {
                    foreach ($leads_details as $k => $v) {
                        $response[$k]['leads_data'] = $v;
                    }
                }

                if(isset($leads_details) && sizeof($leads_details) > 0)
                    $total_leads = sizeof($leads_details);
                else
                    $total_leads = '';
                   
                $user_details = User::getAllDetailsByUserID($sender_id);

                $input['value'] = $user_details->name;
                $input['user_details'] = $user_details;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                $input['response'] = $response;
                $input['total_leads'] = $total_leads;

                \Mail::send('adminlte::emails.userwiseMonthlyReport', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Monthly Activity Report - ' . $input['value'] . ' - ' . date("F",strtotime("last month"))." ".date("Y"));
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Productivity Report') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get user Bench Mark from master
                $user_bench_mark = UserBenchMark::getBenchMarkByUserID($sender_id);

                $year = date('Y');
                $month = date('m');
                $lastDayOfWeek = '7';

                // Get Weeks
                $weeks = Date::getWeeksInMonth($year, $month, $lastDayOfWeek);

                // Set new weeks
                $new_weeks = array();

                if(isset($weeks) && sizeof($weeks) == 6) {

                    // Week1
                    $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                    $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

                    // Week2
                    $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
                    $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

                    // Week3
                    $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
                    $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

                    // Week4
                    $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
                    $new_weeks[3]['to_date'] = $weeks[5]['to_date'];
                }
                else if(isset($weeks) && sizeof($weeks) == 5) {

                    $date1 = $weeks[0]['from_date'];
                    $date2 = $weeks[0]['to_date'];

                    $diff = (strtotime($date2) - strtotime($date1))/24/3600;
                    
                    if($diff > 2) {

                        // Week1
                        $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                        $new_weeks[0]['to_date'] = $weeks[0]['to_date'];

                        // Week2
                        $new_weeks[1]['from_date'] = $weeks[1]['from_date'];
                        $new_weeks[1]['to_date'] = $weeks[1]['to_date'];

                        // Week3
                        $new_weeks[2]['from_date'] = $weeks[2]['from_date'];
                        $new_weeks[2]['to_date'] = $weeks[2]['to_date'];

                        // Week4
                        $last_date1 = $weeks[4]['from_date'];
                        $last_date2 = $weeks[4]['to_date'];

                        $last_diff = (strtotime($last_date2) - strtotime($last_date1))/24/3600;

                        if($last_diff > 1) {

                            $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                            $new_weeks[3]['to_date'] = $weeks[3]['to_date'];

                            $new_weeks[4]['from_date'] = $weeks[4]['from_date'];
                            $new_weeks[4]['to_date'] = $weeks[4]['to_date'];
                        }
                        else {

                            $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                            $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
                        }
                    }
                    else {

                        // Week1
                        $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                        $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

                        // Week2
                        $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
                        $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

                        // Week3
                        $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
                        $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

                        // Week4
                        $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
                        $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
                    }
                }
                else {

                    // Set all Weeks
                    $new_weeks = $weeks;
                }

                // Get no of weeks in month & get from date & to date
                $i=1;
                $frm_to_date_array = array();

                if(isset($new_weeks) && $new_weeks != '') {

                    foreach ($new_weeks as $key => $value) {

                        $no_of_weeks = $i;

                        $frm_to_date_array[$i]['from_date'] = $value['from_date'];
                        $frm_to_date_array[$i]['to_date'] = $value['to_date'];

                        // Get no of cv's associated count in this week
                        $frm_to_date_array[$i]['ass_cnt'] = JobAssociateCandidates::getProductivityReportCVCount($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of shortlisted candidate count in this week
                        $frm_to_date_array[$i]['shortlisted_cnt'] = JobAssociateCandidates::getProductivityReportShortlistedCount($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of interview of candidates count in this week
                        $frm_to_date_array[$i]['interview_cnt'] = Interview::getProductivityReportInterviewCount($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of selected candidate count in this week
                        $frm_to_date_array[$i]['selected_cnt'] = JobAssociateCandidates::getProductivityReportSelectedCount($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of offer acceptance count in this week
                        $frm_to_date_array[$i]['offer_acceptance_ratio'] = Bills::getProductivityReportOfferAcceptanceRatio($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of joining count in this week
                        $frm_to_date_array[$i]['joining_ratio'] = Bills::getProductivityReportJoiningRatio($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of after joining success count in this week
                        $frm_to_date_array[$i]['joining_success_ratio'] = Bills::getProductivityReportJoiningSuccessRatio($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        $i++;
                    }
                }

                if(isset($user_bench_mark) && sizeof($user_bench_mark) > 0) {
                    
                    $user_bench_mark['no_of_resumes_monthly'] = $user_bench_mark['no_of_resumes'];
                    $user_bench_mark['no_of_resumes_weekly'] = number_format($user_bench_mark['no_of_resumes'] / $no_of_weeks);

                    $user_bench_mark['shortlist_ratio_monthly'] = number_format($user_bench_mark['no_of_resumes'] * $user_bench_mark['shortlist_ratio']/100);
                    $user_bench_mark['shortlist_ratio_weekly'] = number_format($user_bench_mark['shortlist_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['interview_ratio_monthly'] = number_format($user_bench_mark['shortlist_ratio_monthly'] * $user_bench_mark['interview_ratio'] / 100);
                    $user_bench_mark['interview_ratio_weekly'] = number_format($user_bench_mark['interview_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['selection_ratio_monthly'] = number_format($user_bench_mark['interview_ratio_monthly'] * $user_bench_mark['selection_ratio'] / 100);
                    $user_bench_mark['selection_ratio_weekly'] = number_format($user_bench_mark['selection_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['offer_acceptance_ratio_monthly'] = number_format($user_bench_mark['selection_ratio_monthly'] * $user_bench_mark['offer_acceptance_ratio'] / 100);
                    $user_bench_mark['offer_acceptance_ratio_weekly'] = number_format($user_bench_mark['offer_acceptance_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['joining_ratio_monthly'] = number_format($user_bench_mark['offer_acceptance_ratio_monthly'] * $user_bench_mark['joining_ratio'] / 100);
                    $user_bench_mark['joining_ratio_weekly'] = number_format($user_bench_mark['joining_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['after_joining_success_ratio_monthly'] = number_format($user_bench_mark['joining_ratio_monthly'] * $user_bench_mark['after_joining_success_ratio'] / 100);
                    $user_bench_mark['after_joining_success_ratio_weekly'] = number_format($user_bench_mark['after_joining_success_ratio_monthly'] / $no_of_weeks);
                }

                // Set last column Monthly Achivment value

                if(isset($frm_to_date_array) && $frm_to_date_array != '') {

                    $no_of_resumes_monthly = '';
                    $shortlist_ratio_monthly = '';
                    $interview_ratio_monthly = '';
                    $selection_ratio_monthly = '';
                    $offer_acceptance_ratio_monthly = '';
                    $joining_ratio_monthly = '';
                    $after_joining_success_ratio_monthly = '';

                    foreach ($frm_to_date_array as $key => $value) {

                        if($no_of_resumes_monthly == '') {

                            $no_of_resumes_monthly = $value['ass_cnt'];
                        }
                        else {

                            $no_of_resumes_monthly = $no_of_resumes_monthly + $value['ass_cnt'];
                        }

                        if($shortlist_ratio_monthly == '') {

                            $shortlist_ratio_monthly = $value['shortlisted_cnt'];
                        }
                        else {

                            $shortlist_ratio_monthly = $shortlist_ratio_monthly + $value['shortlisted_cnt'];
                        }

                        if($interview_ratio_monthly == '') {

                            $interview_ratio_monthly = $value['interview_cnt'];
                        }
                        else {

                            $interview_ratio_monthly = $interview_ratio_monthly + $value['interview_cnt'];
                        }

                        if($selection_ratio_monthly == '') {

                            $selection_ratio_monthly = $value['selected_cnt'];
                        }
                        else {

                            $selection_ratio_monthly =  $selection_ratio_monthly + $value['selected_cnt'];
                        }

                        if($offer_acceptance_ratio_monthly == '') {

                            $offer_acceptance_ratio_monthly = $value['offer_acceptance_ratio'];
                        }
                        else {

                            $offer_acceptance_ratio_monthly = $offer_acceptance_ratio_monthly + $value['offer_acceptance_ratio'];
                        }

                        if($joining_ratio_monthly == '') {

                            $joining_ratio_monthly = $value['joining_ratio'];
                        }
                        else {

                            $joining_ratio_monthly = $joining_ratio_monthly + $value['joining_ratio'];
                        }

                        if($after_joining_success_ratio_monthly == '') {

                            $after_joining_success_ratio_monthly = $value['joining_success_ratio'];
                        }
                        else {

                            $after_joining_success_ratio_monthly = $after_joining_success_ratio_monthly + $value['joining_success_ratio'];
                        }
                    }
                }

                // Get user name
                $user_details = User::getAllDetailsByUserID($sender_id);

                $input['user_bench_mark'] = $user_bench_mark;
                $input['no_of_weeks'] = $no_of_weeks;
                $input['frm_to_date_array'] = $frm_to_date_array;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);
                $input['user_name'] = $user_details->name;

                // Set last column Monthly Achivment value

                if(isset($no_of_resumes_monthly) && $no_of_resumes_monthly > 0) {
                    $input['no_of_resumes_monthly'] = $no_of_resumes_monthly;
                }
                else {
                    $input['no_of_resumes_monthly'] = '';
                }

                if(isset($shortlist_ratio_monthly) && $shortlist_ratio_monthly > 0) {
                    $input['shortlist_ratio_monthly'] = $shortlist_ratio_monthly;
                }
                else {
                    $input['shortlist_ratio_monthly'] = '';
                }

                if(isset($interview_ratio_monthly) && $interview_ratio_monthly > 0) {
                    $input['interview_ratio_monthly'] = $interview_ratio_monthly;
                }
                else {
                    $input['interview_ratio_monthly'] = '';
                }

                if(isset($selection_ratio_monthly) && $selection_ratio_monthly > 0) {
                    $input['selection_ratio_monthly'] = $selection_ratio_monthly;
                }
                else {
                    $input['selection_ratio_monthly'] = '';
                }

                if(isset($offer_acceptance_ratio_monthly) && $offer_acceptance_ratio_monthly > 0) {
                    $input['offer_acceptance_ratio_monthly'] = $offer_acceptance_ratio_monthly;
                }
                else {
                    $input['offer_acceptance_ratio_monthly'] = '';
                }

                if(isset($joining_ratio_monthly) && $joining_ratio_monthly > 0) {
                    $input['joining_ratio_monthly'] = $joining_ratio_monthly;
                }
                else {
                    $input['joining_ratio_monthly'] = '';
                }
                
                if(isset($after_joining_success_ratio_monthly) && $after_joining_success_ratio_monthly > 0) {
                    $input['after_joining_success_ratio_monthly'] = $after_joining_success_ratio_monthly;
                }
                else {
                    $input['after_joining_success_ratio_monthly'] = '';
                }
                
                \Mail::send('adminlte::emails.ProductivityReport', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Productivity Report -'.$input['user_name']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'New Candidate AutoScript Mail') {

                $candidate_details = CandidateBasicInfo::getCandidateDetailsById($module_id);

                $input['candidate_name'] = $candidate_details['full_name'];
                $input['owner_email'] = $candidate_details['owner_email'];

                $input['from_name'] = $candidate_details['owner_first_name'] . " " . $candidate_details['owner_last_name'];

                if($input['owner_email'] == 'careers@adlertalent.com') {

                    \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
                    });
                }
                else {

                    \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->bcc($input['owner_email'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
                    });
                }

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");

                \DB::statement("UPDATE candidate_basicinfo SET autoscript_status = '1' where id = '$module_id';");
            }

            else if ($value['module'] == 'Client 2nd Line Account Manager') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $client = ClientBasicinfo::getClientDetailsById($module_id);

                $input['module'] = $value['module'];
                $input['module_id'] = $value['module_id'];
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
                $input['client'] = $client;

                \Mail::send('adminlte::emails.clientsecondlineamemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == '2nd Line of Multiple Clients') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $client_ids_array = explode(",",$module_id);
                $client_info = array();
                $i=0;

                foreach($client_ids_array as $key => $value) {

                    $client = ClientBasicinfo::getClientDetailsById($value);
                    
                    $client_info[$i]['am_name'] = $client['am_name'];
                    $client_info[$i]['name'] = $client['name'];
                    $client_info[$i]['coordinator_name'] = $client['coordinator_name'];
                    $client_info[$i]['second_line_am_name'] = $client['second_line_am_name'];
                    
                    $i++;
                }

                $input['second_line_am_name'] = $client['second_line_am_name'];
                $input['client_info'] = $client_info;
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;

                \Mail::send('adminlte::emails.multipleclientsecondlineamemail', $input, function ($message) use($input) {
                    
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Lead Bulk Email') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];
                $input['bulk_message'] = $value['message'];

                $input['from_email'] = User::getUserEmailById($value['sender_name']);

                $user_details = User::getAllDetailsByUserID($value['sender_name']);

                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.clientbulkmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->bcc($input['from_email'])->replyTo($input['from_email'], $input['from_name'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == "Today's Interviews") {

                $from_date = date("Y-m-d 00:00:00");
                $to_date = date("Y-m-d 23:59:59");

                $type_array = array();
                $file_path_array = array();
                $j=0;

                $interviews = Interview::getAllInterviewsByReminders($value['sender_name'],$from_date,$to_date);

                if(isset($interviews) && sizeof($interviews) > 0) {

                    foreach ($interviews as $key1 => $value1) {

                        if(isset($value1) && $value1 != '') {

                            $type_array[$j] = $value1['interview_type'];

                            // Candidate Attachments
                            $attachments = CandidateUploadedResume::getCandidateAttachment($value1['candidate_id']);

                            if(isset($attachments) && sizeof($attachments) > 0) {

                                $file_path_all = array();
                                $i=0;

                                foreach ($attachments as $attach_key => $attach_val) {

                                    if (isset($attach_val) && $attach_val != '') {

                                        $file_path = public_path() . "/" . $attach_val->file;
                                    }
                                    else {
                                        $file_path = '';
                                    }
                                    
                                    $file_path_all[$i] = $file_path;
                                    $i++;
                                }
                            }
                            else {

                                $file_path_all = array();
                            }

                            $file_path_array[$j] = $file_path_all;

                            $j++;
                        }
                    }
                }

                $to_array = explode(",",$input['to']);
                $input['to_array'] = $to_array;
                $input['type_string'] = implode(",", $type_array);
                $input['file_path'] = $file_path_array;
                $input['interview_details'] = $interviews;

                \Mail::send('adminlte::emails.interviewmultipleschedule', $input, function ($message) use($input) {

                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->subject($input['subject']);

                    if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                        foreach ($input['file_path'] as $key => $value) {

                            if(isset($value) && $value != '') {

                                foreach ($value as $k1 => $v1) {
                                    $message->attach($v1);
                                }
                            }
                        }
                    }
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == "Yesterday's Interviews") {

                $from_date = date('Y-m-d 00:00:00',strtotime("-1 days"));
                $to_date = date("Y-m-d 23:59:59", strtotime("-1 days"));

                $type_array = array();
                $file_path_array = array();
                $j=0;

                $interviews = Interview::getAllInterviewsByReminders($value['sender_name'],$from_date,$to_date);

                if(isset($interviews) && sizeof($interviews) > 0) {

                    foreach ($interviews as $key1 => $value1) {

                        if(isset($value1) && $value1 != '') {

                            $type_array[$j] = $value1['interview_type'];

                            // Candidate Attachments
                            $attachments = CandidateUploadedResume::getCandidateAttachment($value1['candidate_id']);

                            if(isset($attachments) && sizeof($attachments) > 0) {

                                $file_path_all = array();
                                $i=0;

                                foreach ($attachments as $attach_key => $attach_val) {

                                    if (isset($attach_val) && $attach_val != '') {

                                        $file_path = public_path() . "/" . $attach_val->file;
                                    }
                                    else {
                                        $file_path = '';
                                    }
                                    
                                    $file_path_all[$i] = $file_path;
                                    $i++;
                                }
                            }
                            else {

                                $file_path_all = array();
                            }

                            $file_path_array[$j] = $file_path_all;

                            $j++;
                        }
                    }
                }

                $to_array = explode(",",$input['to']);
                $input['to_array'] = $to_array;
                $input['type_string'] = implode(",", $type_array);
                $input['file_path'] = $file_path_array;
                $input['interview_details'] = $interviews;
                $input['yesterday_date'] = date('Y-m-d',strtotime("-1 days"));

                \Mail::send('adminlte::emails.interviewmultipleschedule', $input, function ($message) use($input) {

                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->subject($input['subject']);

                    if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                        foreach ($input['file_path'] as $key => $value) {

                            if(isset($value) && $value != '') {

                                foreach ($value as $k1 => $v1) {
                                    $message->attach($v1);
                                }
                            }
                        }
                    }
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == "Interview Reminder") {

                $interview_ids_array = explode(",",$module_id);

                if(isset($interview_ids_array) && sizeof($interview_ids_array) > 0) {

                    $type_array = array();
                    $file_path_array = array();
                    $j=0;

                    $interviews = array();

                    foreach ($interview_ids_array as $k1 => $v1) {

                        if(isset($v1) && $v1 != '') {

                            $get_interview_by_id = Interview::getInterviewById($v1);

                            $type_array[$j] = $get_interview_by_id->interview_type;

                            // Candidate Attachments
                            $attachments = CandidateUploadedResume::getCandidateAttachment($get_interview_by_id->candidate_id);

                            if(isset($attachments) && sizeof($attachments) > 0) {

                                $file_path_all = array();
                                $i=0;

                                foreach ($attachments as $attach_key => $attach_val) {

                                    if (isset($attach_val) && $attach_val != '') {

                                        $file_path = public_path() . "/" . $attach_val->file;
                                    }
                                    else {
                                        $file_path = '';
                                    }
                                    
                                    $file_path_all[$i] = $file_path;
                                    $i++;
                                }
                            }
                            else {

                                $file_path_all = array();
                            }

                            $file_path_array[$j] = $file_path_all;
                            $interviews[$j] = $get_interview_by_id;
                        }
                        $j++;
                    }
                }

                if(isset($interviews) && sizeof($interviews) > 0) {

                    $interview_details = array();
                    $i=0;

                    foreach ($interviews as $k2 => $v2) {

                        $interview_details[$i]['id'] = $v2['id'];
                        $interview_details[$i]['client_name'] = $v2['client_name'];
                        $interview_details[$i]['job_designation'] = $v2['posting_title'];

                        if($v2['remote_working'] == '1') {

                            $interview_details[$i]['job_location'] = "Remote";
                        }
                        else {

                            $interview_details[$i]['job_location'] = $v2['job_city'];
                        }

                        $interview_details[$i]['interview_type'] = $v2['interview_type'];
                        $interview_details[$i]['interview_location'] = $v2['interview_location'];
                        
                        $interview_details[$i]['cname'] = $v2['full_name'];
                        $interview_details[$i]['cemail'] = $v2['candidate_email'];
                        $interview_details[$i]['cmobile'] = $v2['candidate_mobile'];
                        $interview_details[$i]['candidate_location'] = $v2['candidate_location'];
                        $interview_details[$i]['skype_id'] = $v2['skype_id'];

                        $datearray = explode(' ', $v2['interview_date']);
                        $interview_date = $datearray[0];
                        $interview_time = $datearray[1];
                        $interview_details[$i]['interview_date'] = $interview_date;
                        $interview_details[$i]['interview_time'] = $interview_time;

                        $i++;   
                    }
                }

                if(isset($interview_details) && sizeof($interview_details) > 0) {
                    
                    $to_array = explode(",",$input['to']);
                    $input['to_array'] = $to_array;
                    $input['type_string'] = implode(",", $type_array);
                    $input['file_path'] = $file_path_array;
                    $input['interview_details'] = $interview_details;

                    \Mail::send('adminlte::emails.interviewmultipleschedule', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->subject($input['subject']);

                        if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                            foreach ($input['file_path'] as $key => $value) {

                                if(isset($value) && $value != '') {

                                    foreach ($value as $k1 => $v1) {
                                        $message->attach($v1);
                                    }
                                }
                            }
                        }
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Update User Informations') {

                $to_array = explode(",",$input['to']);

                // Get users for popup of add information
                $users_array = User::getBefore7daysUsersDetails();

                if(isset($users_array) && sizeof($users_array) > 0) {

                    $input['users_array'] = $users_array;
                    $input['to_array'] = $to_array;

                     \Mail::send('adminlte::emails.adduserotherinformationsemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Email Template') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $email_template_details = EmailTemplate::getEmailTemplateDetailsById($value['module_id']);

                if(isset($email_template_details) && sizeof($email_template_details) > 0) {

                    $input['email_template_details'] = $email_template_details;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                     \Mail::send('adminlte::emails.newemailtemplatemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'New User') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $users_details = User::getProfileInfo($value['module_id']);

                if(isset($users_details) && $users_details != '') {

                    $input['users_details'] = $users_details;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                     \Mail::send('adminlte::emails.useraddemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Contactsphere' || $value['module'] == 'Hold Contact' || $value['module'] == 'Relive Hold Contact' || $value['module'] == 'Forbid Contact' || $value['module'] == 'Relive Forbid Contact') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $contact_details = Contactsphere::getContactDetailsById($value['module_id']);

                if(isset($contact_details) && $contact_details != '') {

                    $input['contact_details'] = $contact_details;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                     \Mail::send('adminlte::emails.contactspheremail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Contact Bulk Email') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];
                $input['bulk_message'] = $value['message'];

                $input['from_email'] = User::getUserEmailById($value['sender_name']);

                $user_details = User::getAllDetailsByUserID($value['sender_name']);

                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.clientbulkmail', $input, function ($message) use($input) {

                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->bcc($input['from_email'])->replyTo($input['from_email'], $input['from_name'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Candidate Information Form') {

                $to_array = explode(",",$input['to']);

                $split_module_id = explode("-",$value['module_id']);

                $candidate_id = $split_module_id[0];
                $job_id = $split_module_id[1];

                // Get users for popup of add information
                $candidate_job_details = CandidateBasicInfo::getCandidateJobDetailsById($candidate_id,$job_id);

                $user_email_details = UsersEmailPwd::getUserEmailDetails($candidate_job_details['owner_id']);

                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                if(isset($candidate_job_details) && $candidate_job_details != '') {

                    // Get candidate owner signature
                    $owner_id = $candidate_job_details['owner_id'];
                    $owner_info = User::getProfileInfo($owner_id);
                    $input['owner_signature'] = $owner_info['signature'];

                    $input['from_name'] = $candidate_job_details['owner_first_name'] . " " . $candidate_job_details['owner_last_name'];
                    
                    $input['candidate_job_details'] = $candidate_job_details;
                    $input['to_array'] = $to_array;
                    $input['bcc_email'] = $candidate_job_details['owner_email'];
                    $input['attachment'] = public_path() . "/" . 'uploads/Candidate_Information_Form_Adler.docx';

                     \Mail::send('adminlte::emails.candidateinformationform', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->bcc($input['bcc_email'])->replyTo($input['bcc_email'], $input['from_name'])->subject($input['subject']);

                        if (isset($input['attachment']) && $input['attachment'] != '') {
                            $message->attach($input['attachment']);
                        }
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Add User Salary Information') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $users_array = User::getBefore7daysUserSalaryDetails();

                if(isset($users_array) && sizeof($users_array) > 0) {

                    $input['users_array'] = $users_array;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                     \Mail::send('adminlte::emails.addusersalaryinformationsemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Applicant Candidates Report') {

                $to_array = explode(",",$input['to']);

                $jobs = JobOpen::getJobsByMB($value['sender_name']);

                if(isset($jobs) && sizeof($jobs) > 0) {

                    foreach ($jobs as $k1 => $v1) {

                        if(isset($v1['applicant_candidates']) && sizeof($v1['applicant_candidates']) > 0) {

                            $input['applicant_candidates'] = $v1['applicant_candidates'];
                            $input['to_array'] = $to_array;

                            \Mail::send('adminlte::emails.applicantcandidatesreport', $input, function ($message) use($input) {

                                $message->from($input['from_address'], $input['from_name']);
                                $message->to($input['to_array'])->subject($input['subject']);
                            });

                            \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                        }
                    }
                }
            }

            else if ($value['module'] == 'Update User') {

                // Get users for popup of add information
                $users_details = User::getProfileInfo($value['module_id']);

                if(isset($users_details) && $users_details != '') {

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Ticket Discussion') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $ticket_res = TicketsDiscussion::getTicketDetailsById($value['module_id']);
                $ticket_res_doc = TicketsDiscussionDoc::getTicketDocsById($value['module_id']);

                if (isset($ticket_res_doc) && $ticket_res_doc != '') {

                    $file_path_array = array();
                    $j=0;

                    foreach ($ticket_res_doc as $k => $v) {

                        $file_path = public_path() . "/" . $v['fileName'];
                        $file_path_array[$j] = $file_path;
                        $j++;
                    }
                }

                if(isset($ticket_res) && sizeof($ticket_res) > 0) {

                    $input['ticket_res'] = $ticket_res;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                    if (isset($file_path_array) && sizeof($file_path_array) > 0) {

                        $input['file_path'] = $file_path_array;
                    }

                    \Mail::send('adminlte::emails.ticketdiscussionemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);

                        if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                            foreach ($input['file_path'] as $key => $value) {

                                if(isset($value) && $value != '') {
                                    $message->attach($value);
                                }
                            }
                        }
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Ticket Discussion Comment') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $post_res = TicketDiscussionPost::getTicketPostDetailsById($value['module_id']);
                $ticket_post_res_doc = TicketsDiscussionPostDoc::getTicketPostDocsById($value['module_id']);

                if (isset($ticket_post_res_doc) && $ticket_post_res_doc != '') {

                    $file_path_array = array();
                    $j=0;

                    foreach ($ticket_post_res_doc as $k => $v) {

                        $file_path = public_path() . "/" . $v['fileName'];
                        $file_path_array[$j] = $file_path;
                        $j++;
                    }
                }

                // Get Ticket Details from Post ID
                $ticket_res = TicketsDiscussion::getTicketDetailsById($post_res['tickets_discussion_id']);

                if(isset($post_res) && sizeof($post_res) > 0) {

                    $input['ticket_res'] = $ticket_res;
                    $input['post_res'] = $post_res;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                    if (isset($file_path_array) && sizeof($file_path_array) > 0) {

                        $input['file_path'] = $file_path_array;
                    }

                    \Mail::send('adminlte::emails.ticketdiscussionpostemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);

                        if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                            foreach ($input['file_path'] as $key => $value) {

                                if(isset($value) && $value != '') {
                                    $message->attach($value);
                                }
                            }
                        }
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Work Planning') {

                $to_array = explode(",",$input['to']);
                $input['to_array'] = $to_array;
              
                $input['module_id'] = $value['module_id'];

                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;
                $input['owner_email'] = $user_details->email;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);
                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                $work_planning = WorkPlanning::getWorkPlanningDetailsById($value['module_id']);
                $work_planning_list = WorkPlanningList::getWorkPlanningList($value['module_id']);

                $today_date = $work_planning['added_date'];
                $report_delay = $work_planning['report_delay'];
                $report_delay_content = $work_planning['report_delay_content'];
                $link = $work_planning['link'];
                $total_projected_time = $work_planning['total_projected_time'];
                $total_actual_time = $work_planning['total_actual_time'];

                $input['today_date'] = $today_date;
                $input['report_delay'] = $report_delay;
                $input['report_delay_content'] = $report_delay_content;
                $input['link'] = $link;
                $input['total_projected_time'] = $total_projected_time;
                $input['total_actual_time'] = $total_actual_time;
                $input['module'] = $value['module'];
                $input['work_planning_list'] = $work_planning_list;

                \Mail::send('adminlte::emails.workplanningmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->bcc($input['owner_email'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Work Planning Remarks') {

                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];

                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;
                $input['owner_email'] = $user_details->email;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);
                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                $work_planning = WorkPlanning::getWorkPlanningDetailsById($value['module_id']);

                // Get Task List
                $work_planning_list = WorkPlanningList::getWorkPlanningList($value['module_id']);

                $today_date = $work_planning['added_date'];
                $report_delay = $work_planning['report_delay'];
                $report_delay_content = $work_planning['report_delay_content'];
                $link = $work_planning['link'];
                $total_projected_time = $work_planning['total_projected_time'];
                $total_actual_time = $work_planning['total_actual_time'];

                $input['today_date'] = $today_date;
                $input['report_delay'] = $report_delay;
                $input['report_delay_content'] = $report_delay_content;
                $input['link'] = $link;
                $input['module'] = $value['module'];
                $input['total_projected_time'] = $total_projected_time;
                $input['total_actual_time'] = $total_actual_time;
                $input['work_planning_list'] = $work_planning_list;

                \Mail::send('adminlte::emails.workplanningmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == "Hiring Report") {

                $job_ids_array = explode(",",$value['module_id']);

                $from_date = date('Y-m-d',strtotime("monday this week"));
                $to_date = date('Y-m-d',strtotime("$from_date +6days"));

                if(isset($job_ids_array) && sizeof($job_ids_array) > 0) {

                    $j=0;
                    $list_array = array();

                    foreach ($job_ids_array as $k1 => $v1) {

                        if(isset($v1) && $v1 != '') {

                            $associate_candidates = JobAssociateCandidates::getAssociatedCandidatesByWeek($v1,$from_date,$to_date);

                            $list_array[$j]['associate_candidates'] = $associate_candidates;

                            $shortlisted_candidates = JobAssociateCandidates::getShortlistedCandidatesByWeek($v1,$from_date,$to_date);

                            $list_array[$j]['shortlisted_candidates'] = $shortlisted_candidates;

                            $attended_interviews = Interview::getAttendedInterviewsByWeek($v1,$from_date,$to_date);

                            $list_array[$j]['attended_interviews'] = $attended_interviews;

                            $job_details = JobOpen::getJobById($v1);
                            $list_array[$j]['posting_title'] = $job_details['posting_title'];

                            $client_info = ClientBasicinfo::getClientInfoByJobId($v1);
                            $client_name = $client_info['coordinator_prefix'] . " " .  $client_info['coordinator_name'];

                            $j++;
                        }
                    }
                }

                if(isset($list_array) && sizeof($list_array) > 0) {
                    
                    $to_array = explode(",",$input['to']);
                    $input['to_array'] = $to_array;

                    $input['list_array'] = $list_array;
                    $input['client_name'] = $client_name;

                    $owner_details = User::getAllDetailsByUserID($value['sender_name']);
                    $input['client_owner'] = $owner_details['name'];

                    \Mail::send('adminlte::emails.clientautogeneratereportemail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Exist Candidate AutoScript Mail') {

                $candidate_details = CandidateBasicInfo::getCandidateDetailsById($module_id);

                $input['candidate_name'] = $candidate_details['full_name'];
                $input['owner_email'] = $candidate_details['owner_email'];

                if($input['owner_email'] == 'careers@adlertalent.com') {

                    \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
                    });
                }
                else {

                    $input['from_name'] = $candidate_details['owner_first_name'] . " " . $candidate_details['owner_last_name'];

                    \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->bcc($input['owner_email'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
                    });
                }

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");

                \DB::statement("UPDATE candidate_basicinfo SET autoscript_status = '1' where id = '$module_id';");
            }

            else if ($value['module'] == 'Work Planning Rejection') {

                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];

                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;
                $input['owner_email'] = $user_details->email;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);
                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                $work_planning = WorkPlanning::getWorkPlanningDetailsById($value['module_id']);

                // Get Task List
                $work_planning_list = WorkPlanningList::getWorkPlanningList($value['module_id']);

                $today_date = $work_planning['added_date'];
                $report_delay = $work_planning['report_delay'];
                $report_delay_content = $work_planning['report_delay_content'];
                $link = $work_planning['link'];
                $reject_reply = $work_planning['reject_reply'];
                $reason_of_rejection = $work_planning['reason_of_rejection'];
                $total_projected_time = $work_planning['total_projected_time'];
                $total_actual_time = $work_planning['total_actual_time'];

                $input['today_date'] = $today_date;
                $input['report_delay'] = $report_delay;
                $input['report_delay_content'] = $report_delay_content;
                $input['link'] = $link;
                $input['reject_reply'] = $reject_reply;
                $input['reason_of_rejection'] = $reason_of_rejection;
                $input['module'] = $value['module'];
                $input['total_projected_time'] = $total_projected_time;
                $input['total_actual_time'] = $total_actual_time;
                $input['work_planning_list'] = $work_planning_list;
                

                \Mail::send('adminlte::emails.workplanningmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }
        }
    }
}