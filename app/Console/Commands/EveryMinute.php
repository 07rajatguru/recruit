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

                $cc_array = array();
                $cc_array = explode(",",$input['cc']); 

                $input['cc_array'] = array_unique($cc_array);

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
                    $client_history = ClientTimeline::getDetailsByClientId($value);

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

                \Mail::send('adminlte::emails.leavemail', $input, function ($message) use ($input) {
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
                    $message->to($input['to_array'])->cc($input['cc_array'])->bcc($input['owner_email'])->subject($input['subject']);
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

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $superAdminUserID = getenv('SUPERADMINUSERID');
                $managerUserID = getenv('MANAGERUSERID');

                $access_roles_id = array($superAdminUserID,$managerUserID);

                if(in_array($value['sender_name'],$access_roles_id)) {
                    $users = User::getAllUsersExpectSuperAdmin($recruitment);
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

                $weeks = Date::getWeeksInMonth($year, $month, $lastDayOfWeek);

                $dates_array = array();
                $i=0;

                foreach ($weeks as $key => $val) {

                    $dates_array[$i] = implode("--",$val);
                    $i++;
                }

                // Get no of weeks in month & get from date & to date
                $i=1;
                $frm_to_date_array = array();

                if(isset($dates_array) && $dates_array != '') {

                    foreach ($dates_array as $key => $value) {

                        $no_of_weeks = $i;

                        $frm_to_array = explode("--", $value);

                        $frm_to_date_array[$i]['from_date'] = $frm_to_array[0];
                        $frm_to_date_array[$i]['to_date'] = $frm_to_array[1];

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

                // Get user name

                $user_details = User::getAllDetailsByUserID($sender_id);

                $input['user_bench_mark'] = $user_bench_mark;
                $input['no_of_weeks'] = $no_of_weeks;
                $input['frm_to_date_array'] = $frm_to_date_array;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);
                $input['user_name'] = $user_details->name;

                \Mail::send('adminlte::emails.ProductivityReport', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Productivity Report -'.$input['user_name']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'New Candidate AutoScript Mail') {

                // get todos subject and description

                $candidate_details = CandidateBasicInfo::getCandidateDetailsById($module_id);

                $input['candidate_name'] = $candidate_details['full_name'];
                $input['owner_email'] = $candidate_details['owner_email'];

                \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->bcc($input['owner_email'])->subject($input['subject']);
                });
           
               
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
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
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
                                $message->attach($value);
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
                                $message->attach($value);
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

                            // Candidate Attachment
                            $attachment = CandidateUploadedResume::getCandidateAttachment($get_interview_by_id->candidate_id);

                            if (isset($attachment) && $attachment != '') {
                                    $file_path = public_path() . "/" . $attachment->file;
                            }
                            else {
                                    $file_path = '';
                            }

                            $file_path_array[$j] = $file_path;

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

                            $interview_details[$i]['job_location'] = "Remote Working";
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
                                    $message->attach($value);
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
        }
    }
}