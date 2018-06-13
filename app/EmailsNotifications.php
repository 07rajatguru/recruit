<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailsNotifications extends Model
{
    //
    use SoftDeletes;

    public $table = "emails_notification";

    public function logNotifications($data){

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

    public static function getShowJobs($id){

        $query = EmailsNotifications::query();
        $query = $query->join('job_openings','job_openings.id','=','emails_notification.module_id');
        $query = $query->join('client_basicinfo', 'client_basicinfo.id', '=', 'job_openings.client_id');
        $query = $query->join('users', 'users.id', '=', 'job_openings.hiring_manager_id');
        $query = $query->join('industry', 'industry.id', '=', 'job_openings.industry_id');
        $query = $query->select('emails_notification.module_id as module_id','job_openings.*', 'client_basicinfo.name as client_name', 'users.name as hiring_manager_name', 'industry.name as industry_name');
        $query = $query->where('emails_notification.id',$id);
        $query_res = $query->get();

        $job_open = array();

        foreach ($query_res as $key => $value) {

            $job_open['module_id'] = $value->module_id;

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else{
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else{
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
            //$job_open['job_opening_status'] = $value->job_opening_status;
            $job_open['desired_candidate'] = $value->desired_candidate;
            $job_open['hiring_manager_name'] = $value->hiring_manager_name;
            //$job_open['hiring_manager_id'] = $value->hiring_manager_id;
            $job_open['no_of_positions'] = $value->no_of_positions;
            $job_open['target_date'] = $value->target_date;
            $job_open['date_opened'] = $value->date_opened;
            $job_open['job_type'] = $value->job_type;
            $job_open['industry_name'] = $value->industry_name;
            $job_open['description'] = strip_tags($value->job_description);
            $job_open['work_experience'] = $value->work_exp_from . "-" . $value->work_exp_to;
            $job_open['salary'] = $salary;
            $job_open['country'] = $value->country;
            $job_open['state'] = $value->state;
            $job_open['city'] = $value->city;
            $job_open['education_qualification'] = $value->qualifications;


            $user = \Auth::user();
            $user_id = $user->id;
            $user_role_id = User::getLoggedinUserRole($user);

            $admin_role_id = env('ADMIN');
            $director_role_id = env('DIRECTOR');
            $manager_role_id = env('MANAGER');
            $superadmin_role_id = env('SUPERADMIN');

            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);

            if(in_array($user_role_id,$access_roles_id)){
                $job_open['access'] = '1';
            }
            else{
                if($value->hiring_manager_id==$user_id){
                    $job_open['access'] = '1';
                }
                else{
                    $job_open['access'] = '0';
                }
            }


            // already added posting,massmail and job search options
            $selected_posting = array();
            $selected_mass_mail = array();
            $selected_job_search = array();

            $mo_posting = '';
            $mo_mass_mail='';
            $mo_job_search = '';
            if(isset($value->posting) && $value->posting!=''){
                $mo_posting = $value->posting;
                $selected_posting = explode(",",$mo_posting);
            }
            if(isset($value->mass_mail) && $value->mass_mail!=''){
                $mo_mass_mail = $value->mass_mail;
                $selected_mass_mail = explode(",",$mo_mass_mail);
            }
            if(isset($value->job_search) && $value->job_search!=''){
                $mo_job_search = $value->job_search;
                $selected_job_search = explode(",",$mo_job_search);
            }

        $job_visible_users = \DB::table('job_visible_users')
            ->select('users.id','users.name')
            ->join('users','users.id','=','job_visible_users.user_id')
            ->join('emails_notification','emails_notification.module_id','=','job_visible_users.job_id')
            ->where('emails_notification.id',$id)
            ->get();
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
            ->where('emails_notification.id','=', $id)
            ->get();

        $utils = new Utils();
        foreach ($jobopen_doc as $key => $value) {
            $job_open['doc'][$i]['name'] = $value->name;
            $job_open['doc'][$i]['id'] = $value->id;
            $job_open['doc'][$i]['url'] = "../" . $value->file;//$utils->getUrlAttribute($value->file);
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

        //print_r($job_open);exit;
        return $job_open; 
    }
}
