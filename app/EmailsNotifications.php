<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailsNotifications extends Model
{
    use SoftDeletes;

    public $table = "emails_notification";

    public function logNotifications($data) {

        $module = $data['module'];
        $sender_name = $data['sender_name'];
        $to = $data['to'];
        $subject = $data['subject'];
        $message = $data['message'];

        $emailnotification = new EmailsNotifications();
        $emailnotification->module = $module;
        $emailnotification->sender_name = $sender_name;
        $emailnotification->to = $to;
        $emailnotification->subject = $subject;
        $emailnotification->message = $message;
        $emailnotification->save();
    }

    public static function getShowJobs($id) {

        $query = EmailsNotifications::query();
        $query = $query->join('job_openings','job_openings.id','=','emails_notification.module_id');
        $query = $query->join('client_basicinfo', 'client_basicinfo.id', '=', 'job_openings.client_id');
        $query = $query->join('users', 'users.id', '=', 'job_openings.hiring_manager_id');
        $query = $query->join('industry', 'industry.id', '=', 'job_openings.industry_id');

        $query = $query->select('emails_notification.module_id as module_id','emails_notification.sender_name as sender_name','job_openings.*', 'client_basicinfo.name as client_name', 'users.name as hiring_manager_name', 'industry.name as industry_name');
        $query = $query->where('emails_notification.id',$id);
        $query_res = $query->get();

        $job_open = array();
        $dateClass = new Date();

        foreach ($query_res as $key => $value) {

            $job_open['module_id'] = $value->module_id;

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            $salary = $min_ctc.'-'.$max_ctc;

            $job_open['posting_title'] = $value->posting_title;

            $job_open['job_id'] = $value->job_id;
            $job_open['client_name'] = $value->client_name;
            $job_open['client_id'] = $value->client_id;
            $job_open['desired_candidate'] = $value->desired_candidate;
            $job_open['hiring_manager_name'] = $value->hiring_manager_name;
            $job_open['no_of_positions'] = $value->no_of_positions;
            $job_open['target_date'] = $dateClass->changeYMDtoDMY($value->target_date);
            $job_open['date_opened'] = $dateClass->changeYMDtoDMY($value->date_opened);
            $job_open['job_type'] = $value->job_type;
            $job_open['industry_name'] = $value->industry_name;
            $job_open['description'] = $value->job_description;
            $job_open['work_experience'] = $value->work_exp_from . "-" . $value->work_exp_to;
            $job_open['salary'] = $salary;
            $job_open['country'] = $value->country;
            $job_open['state'] = $value->state;
            $job_open['city'] = $value->city;

            $job_open['education_qualification'] = $value->qualifications;
            $job_open['sender_name'] = $value->sender_name;

            if($value->remote_working == '1') {

                $job_open['remote_working'] = "Remote";
            }
            else {

                $job_open['remote_working'] = '';
            }

            // already added posting,massmail and job search options
            $selected_posting = array();
            $selected_mass_mail = array();
            $selected_job_search = array();

            $mo_posting = '';
            $mo_mass_mail='';
            $mo_job_search = '';

            if(isset($value->posting) && $value->posting!='') {
                $mo_posting = $value->posting;
                $selected_posting = explode(",",$mo_posting);
            }
            if(isset($value->mass_mail) && $value->mass_mail!='') {
                $mo_mass_mail = $value->mass_mail;
                $selected_mass_mail = explode(",",$mo_mass_mail);
            }
            if(isset($value->job_search) && $value->job_search!='') {
                $mo_job_search = $value->job_search;
                $selected_job_search = explode(",",$mo_job_search);
            }

            $job_visible_users = \DB::table('job_visible_users')
            ->select('users.id','users.name')
            ->join('users','users.id','=','job_visible_users.user_id')
            ->join('emails_notification','emails_notification.module_id','=','job_visible_users.job_id')->where('emails_notification.id',$id)->get();

            $count = 0;
            foreach ($job_visible_users as $key => $value) {
                $job_open['users'][$count] = $value->name;
                $count++;
            }

            $jobopen_model = new JobOpen();
            $upload_type = $jobopen_model->upload_type;

            $i = 0;
            $job_open['doc'] = array();
            $jobopen_doc = \DB::table('job_openings_doc')
            ->join('users', 'users.id', '=', 'job_openings_doc.uploaded_by')
            ->join('emails_notification','emails_notification.module_id','=','job_openings_doc.job_id')
            ->select('job_openings_doc.*', 'users.name as upload_name')
            ->where('emails_notification.id','=', $id)->get();

            $utils = new Utils();
            foreach ($jobopen_doc as $key => $value) {

                $job_open['doc'][$i]['name'] = $value->name;
                $job_open['doc'][$i]['id'] = $value->id;
                $job_open['doc'][$i]['url'] = "../" . $value->file;
                $job_open['doc'][$i]['category'] = $value->category;
                $job_open['doc'][$i]['uploaded_by'] = $value->upload_name;
                $job_open['doc'][$i]['size'] = $utils->formatSizeUnits($value->size);
                $i++;
                
                if (array_search($value->category, $upload_type)) {
                    unset($upload_type[array_search($value->category, $upload_type)]);
                }
            }
            $upload_type['Others'] = 'Others';

            $posting_status = JobOpen::getJobPostingStatus();
            $job_search = JobOpen::getJobSearchOptions();
            $job_status = JobOpen::getJobStatus();
        }
        return $job_open; 
    }

    public static function getAllEmailNotifications($module,$from_date,$to_date) {

        $query = EmailsNotifications::query();
        $query = $query->where('module','=',$module);
        $query = $query->where('status','=',1);

        $query = $query->where(function($query) use ($from_date,$to_date) {

            $query = $query->where('sent_date','>=',$from_date);
            $query = $query->where('sent_date','<=',$to_date);

        });
        
        $query = $query->select('emails_notification.module_id');
        $response = $query->get();

        return $response;
    }
}