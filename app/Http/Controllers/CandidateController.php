<?php

namespace App\Http\Controllers;

use App\CandidateBasicInfo;
use App\CandidateOtherInfo;
use App\CandidateUploadedResume;
use App\docParser;
use App\pdfParser;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use App\User;
use App\JobOpen;
use App\JobAssociateCandidates;
use App\JobCandidateJoiningdate;
use Excel;
use DB;
use App\Bills;
use App\FunctionalRoles;
use App\EducationQualification;
    
class CandidateController extends Controller
{
    //
    public function fullname(){
        
        $candidate_fullname = CandidateBasicInfo::select('candidate_basicinfo.id as id', 'candidate_basicinfo.fname as fname', 'candidate_basicinfo.lname as lname')
                                               // ->limit(20)
                                                
                                                ->get();
        
        $i = 0;
        foreach ($candidate_fullname as $candidatefullname) {
            $id[$i] = $candidatefullname->id;
            $fname[$i] = $candidatefullname->fname;
            $lname[$i] = $candidatefullname->lname;

            $fid =  $id[$i];
            $ffullname = $fname[$i];
            $lfullname = $lname[$i];
            $fullname = $ffullname. ' ' . $lfullname;
            DB::statement("UPDATE candidate_basicinfo SET full_name = '$fullname' where id=$fid");
        $i++; 
        }
           //print_r($fullname);exit;


    }

    // Candidate Fix salary set to job_candidate_joining table from bills
    public function candidatesalary(){

        $candidate = JobCandidateJoiningdate::select('job_candidate_joining_date.job_id as job_id','job_candidate_joining_date.candidate_id as candidate_id')->get();

        $ids = array();
        $i = 0;
        foreach ($candidate as $key => $value) {
            $ids[$i]['job_id'] = $value->job_id;
            $ids[$i]['candidate_id'] = $value->candidate_id;

            $salary = Bills::getCandidatesalaryByJobidCandidateid($ids[$i]['job_id'],$ids[$i]['candidate_id']);

            
            //print_r($salary);exit;
            $job_id = $ids[$i]['job_id'];
            $candidate_id = $ids[$i]['candidate_id'];
            DB::statement("UPDATE job_candidate_joining_date SET fixed_salary = '$salary' where job_id=$job_id and candidate_id=$candidate_id");
            $i++;
        }
    }

    public function index(){

        /*$user =  \Auth::user();

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();

        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);



        $candidateDetails = CandidateBasicInfo::leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id')
            ->leftjoin('users','users.id','=','candidate_otherinfo.owner_id')
            ->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.email as email', 'users.name as owner', 'candidate_basicinfo.mobile as mobile')
            ->orderBy('candidate_basicinfo.id','desc')
            ->get();

        $count = sizeof($candidateDetails);*/

        $letter = 'Z';
        $letter_array = array();
        $range = range("A", "Z");
        foreach ($range as $key => $value) {
            $letter_array[$value] = $value;
        }

        $count = CandidateBasicInfo::getAllCandidatesCount('',$letter);
        
        return view('adminlte::candidate.index',/*array('candidates' => $candidateDetails,'count' => sizeof($candidateDetails)),*/compact('count','letter','letter_array'));
    }

    public function applicantIndex()
    {
        $count = CandidateBasicInfo::getApplicantCandidatesCount('');
        return view('adminlte::candidate.applicantindex',compact('count'));
    }

    public static function getCandidateOrderColumnName($order){
        $order_column_name = '';
        if (isset($order) && $order >= 0) {
            if ($order == 0) {
                $order_column_name = "candidate_basicinfo.id";
            }
            else if ($order == 2) {
                $order_column_name = "candidate_basicinfo.full_name";
            }
            else if ($order == 3) {
                $order_column_name = "users.name";
            }
            else if ($order == 4) {
                $order_column_name = "candidate_basicinfo.email";
            }
            else if ($order == 5) {
                $order_column_name = "candidate_basicinfo.mobile";
            }
            else if ($order == 6) {
                $order_column_name = "functional_roles.name";
            }
        }
        return $order_column_name;
    }

    public function getAllCandidates(){

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $initial_letter = $_GET['initial_letter'];

        $order_column_name = self::getCandidateOrderColumnName($order);
        $response = CandidateBasicInfo::getAllCandidatesDetails($limit,$offset,$search,$order_column_name,$type,$initial_letter);
        $count = CandidateBasicInfo::getAllCandidatesCount($search,$initial_letter);

        $candidate_details = array();
        $i = 0;$j = 0;
        foreach ($response as $key => $value) {
            $action = '';
            $action .= '<a class="fa fa-circle" href="'.route('candidate.show',$value['id']).'" title="Show" style = "margin:3px"></a>';
            $action .= '<a class="fa fa-edit" href="'.route('candidate.edit',$value['id']).'" title="Edit" style = "margin:3px"></a>';
            if ($isSuperAdmin) {
                $delete_view = \View::make('adminlte::partials.deleteModal',['data' => $value, 'name' => 'candidate', 'display_name'=>'Candidate','form_name' => 'login']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            $data = array(++$j,$action,$value['full_name'],$value['owner'],$value['email'],$value['mobile']);
            $candidate_details[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $candidate_details
        );
        //print_r($json_data);exit;

        echo json_encode($json_data);exit;
    }

    public function getAllApplicantCandidates(){

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $order_column_name = self::getCandidateOrderColumnName($order);
        $response = CandidateBasicInfo::getApplicantCandidatesDetails($limit,$offset,$search,$order_column_name,$type);
        $count = CandidateBasicInfo::getApplicantCandidatesCount($search);

        $candidate_details = array();
        $i = 0;$j = 0;
        $all_users = User::getAllUsers('recruiter');

        foreach ($response as $key => $value) {
            $action = '';
            $action .= '<a class="fa fa-circle" href="'.route('applicant-candidate.show',$value['id']).'" title="Show" style = "margin:3px"></a>';
            $action .= '<a class="fa fa-edit" href="'.route('applicant-candidate.edit',$value['id']).'" title="Edit" style = "margin:3px"></a>';
            if ($isSuperAdmin) {
                $delete_view = \View::make('adminlte::partials.deleteModal',['data' => $value, 'name' => 'candidate', 'display_name'=>'Candidate','form_name' => 'applicant']);
                $delete = $delete_view->render();
                $action .= $delete;

                $candidate_owner_view = \View::make('adminlte::partials.candidate_owner', ['data' => $value, 'name' => 'candidate','all_users' => $all_users]);
                $owner = $candidate_owner_view->render();
                $action .= $owner;
            }

            $functional_roles_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['functional_roles_name'].'</a>';

            $data = array(++$j,$action,$value['full_name'],$value['owner'],$value['email'],$value['mobile'],$functional_roles_name);
            $candidate_details[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $candidate_details
        );
        
        //print_r($json_data);exit;

        echo json_encode($json_data);exit;
    }

    public function candidatejoin(){

        $user =  \Auth::user();

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();

        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        if($isSuperAdmin){
            $response = JobCandidateJoiningdate::getJoiningCandidateByUserId($user->id,1);
            $count = sizeof($response);
        }
        else{
            $response = JobCandidateJoiningdate::getJoiningCandidateByUserId($user->id,0);
            $count = sizeof($response);
        }
        
        return view('adminlte::candidate.candidatejoin', array('candidates' => $response,'count' => $count,compact('isSuperAdmin')));
    }

    public function create(){

        $candidateSex = CandidateBasicInfo::getTypeArray();
        $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();
        $candidateSource = CandidateBasicInfo::getCandidateSourceArray();
        $candidateStatus = CandidateBasicInfo::getCandidateStatusArray();

        //$jobopen = JobOpen::getJobOpen();

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title'].",".$v['location'];
        }


        $job_id = 0;
        $viewVariable = array();
        $viewVariable['candidateSex'] = $candidateSex;
        $viewVariable['maritalStatus'] = $maritalStatus;
        $viewVariable['candidateSource'] = $candidateSource;
        $viewVariable['candidateStatus'] = $candidateStatus;
        $viewVariable['emailDisabled'] = '';
        $viewVariable['jobopen'] = $jobopen;
        $viewVariable['job_id'] = $job_id;
        $viewVariable['action'] = 'add';

        return view('adminlte::candidate.create',$viewVariable);
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;

        $candidateSex = $request->input('candidateSex');
        $candiateMaritalStatus = $request->input('maritalStatus');
        $candiateFname = $request->input('fname');
       // $candiateLname = $request->input('lname');
        $candiateMobile = $request->input('mobile');
        $candiatePhone = $request->input('phone');
     //   $candiateFAX = $request->input('fax');
        $candiateStreet1 = $request->input('street1');
        $candiateStreet2 = $request->input('street2');
        $candiateCity = $request->input('city');
        $candiateState = $request->input('state');
        $candiateCountry = $request->input('country');
        $candiateZipCode = $request->input('zipcode');
        $candidateEmail = $request->input('email');
      //  $candidatejobopen = $request->input('jobopen');

        $candiateHighest_qualification = $request->input('highest_qualification');
        $candiateExperience_years = $request->input('experience_years');
        $candiateExperience_months = $request->input('experience_months');
        $candiateCurrent_job_title = $request->input('current_job_title');
        $candiateCurrent_employer = $request->input('current_employer');
        $candiateExpected_salary = $request->input('expected_salary');
        $candiateCurrent_salary = $request->input('current_salary');
        $candiateSkill = $request->input('skill');
        $candiateSkype_id = $request->input('skype_id');
        $candiateStatus = $request->input('candidateStatus');
        $candidateSource = $request->input('candidateSource');
        $job_id = $request->input('jobopen');

        // Save Candidate Basic Info
        $candidate = new CandidateBasicInfo();

        if(isset($candidateSex)){
            $candidate->type = $candidateSex;
        }
        if(isset($candiateMaritalStatus)){
            $candidate->marital_status = $candiateMaritalStatus;
        }
        if(isset($candiateFname)){
            $candidate->full_name = $candiateFname;
        }
       /* if(isset($candiateFname)){
            $candidate->lname = $candiateLname;
        }*/
        if(isset($candidateEmail)){
            $candidate->email = $candidateEmail;
        }
        if(isset($candiateMobile)){
            $candidate->mobile = $candiateMobile;
        }
        if(isset($candiatePhone)){
            $candidate->phone = $candiatePhone;
        }
        if(isset($candiateFAX)){
            $candidate->fax = $candiateFAX;
        }
        if(isset($candiateStreet1)){
            $candidate->street1 = $candiateStreet1;
        }
        if(isset($candiateStreet2)){
            $candidate->street2 = $candiateStreet2;
        }
        if(isset($candiateCity)){
            $candidate->city = $candiateCity;
        }
        if(isset($candiateState)){
            $candidate->state = $candiateState;
        }
        if(isset($candiateCountry)){
            $candidate->country = $candiateCountry;
        }
        if(isset($candiateZipCode)){
            $candidate->zipcode = $candiateZipCode;
        }

        $validator = \Validator::make(Input::all(),$candidate::$rules);

        if($validator->fails()){
            return redirect('candidate/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $candidateStored = $candidate->save();

        if($candidateStored){
            $candidate_id = $candidate->id;

            // Save Candidate Other Info

            $candidateOtherInfo = new CandidateOtherInfo();

            $candidateOtherInfo->candidate_id = $candidate_id;
            if(isset($candiateHighest_qualification)){
                $candidateOtherInfo->highest_qualification = $candiateHighest_qualification;
            }
            if(isset($candiateExperience_years) && $candiateExperience_years!=''){
                $candidateOtherInfo->experience_years = $candiateExperience_years;
            }
            if(isset($candiateExperience_months) && $candiateExperience_months!=''){
                $candidateOtherInfo->experience_months = $candiateExperience_months;
            }
            if(isset($candiateCurrent_job_title)){
                $candidateOtherInfo->current_job_title = $candiateCurrent_job_title;
            }
            if(isset($candiateCurrent_employer)){
                $candidateOtherInfo->current_employer = $candiateCurrent_employer;
            }
            if(isset($candiateExpected_salary) && $candiateExpected_salary!=''){
                $candidateOtherInfo->expected_salary = $candiateExpected_salary;
            }
            if(isset($candiateCurrent_salary) && $candiateCurrent_salary!=''){
                $candidateOtherInfo->current_salary = $candiateCurrent_salary;
            }
            if(isset($candiateSkill)){
                $candidateOtherInfo->skill = $candiateSkill;
            }
            if(isset($candiateSkype_id)){
                $candidateOtherInfo->skype_id = $candiateSkype_id;
            }
            if(isset($candiateStatus) && $candiateStatus>0){
                $candidateOtherInfo->status_id = $candiateStatus;
            }
            if(isset($candidateSource)){
                $candidateOtherInfo->source_id = $candidateSource;
            }
            if(isset($user_id)){
                $candidateOtherInfo->owner_id = $user_id;
            }
          
        
            
            /*$candidateOtherInfo->candidate_id = $candidate_id;
            $candidateOtherInfo->highest_qualification = $request->input('highest_qualification');
            $candidateOtherInfo->experience_years = $request->input('experience_years');
            $candidateOtherInfo->experience_months = $request->input('experience_months');
            $candidateOtherInfo->current_job_title = $request->input('current_job_title');
            $candidateOtherInfo->current_employer = $request->input('current_employer');
            $candidateOtherInfo->expected_salary = $request->input('expected_salary');
            $candidateOtherInfo->current_salary = $request->input('current_salary');
            $candidateOtherInfo->skill = $request->input('skill');
            $candidateOtherInfo->skype_id = $request->input('skype_id');
            $candidateOtherInfo->status_id = $request->input('candidateStatus');
            $candidateOtherInfo->source_id = $request->input('candidateSource');
            $candidateOtherInfo->owner_id = $user_id;*/

            $candidateOtherInfoStored = $candidateOtherInfo->save();

            if($candidateOtherInfoStored){

                // Save Candidate Documentes

                $fileResume = $request->file('resume');
                $fileFormattedResume = $request->file('formatted_resume');
                $fileCoverLatter = $request->file('cover_latter');
                $fileOthers = $request->file('others');

                if(isset($fileResume) && $fileResume->isValid()){
                    $fileResumeName = $fileResume->getClientOriginalName();
                    $fileResumeExtention = $fileResume->getClientOriginalExtension();
                    $fileResumeRealPath = $fileResume->getRealPath();
                    $fileResumeSize = $fileResume->getSize();
                    $fileResumeMimeType = $fileResume->getMimeType();

                    $extention = File::extension($fileResumeName);

                    $fileResumeNameArray = explode('.',$fileResumeName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileResumeNameArray[0]);
                    $fileResumeNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileResume->move($dir,$fileResumeNewName);

                    $fileResumeNewPath = $dir.$fileResumeNewName;

                    $candidateFileUpload = new CandidateUploadedResume();
                    $candidateFileUpload->candidate_id = $candidate_id;
                    $candidateFileUpload->uploaded_by = $user_id;
                    $candidateFileUpload->file_name = $fileResumeNewName;
                    $candidateFileUpload->file_type = 'Candidate Resume';
                    $candidateFileUpload->file = $fileResumeNewPath;
                    $candidateFileUpload->mime = $fileResumeMimeType;
                    $candidateFileUpload->size = $fileResumeSize;
                    $candidateFileUpload->uploaded_date = date('Y-m-d');
                    $candidateFileUploadStored = $candidateFileUpload->save();
                }

                if(isset($fileFormattedResume) && $fileFormattedResume->isValid()){
                    $fileFormattedResumeName = $fileFormattedResume->getClientOriginalName();
                    $fileFormattedResumeExtention = $fileFormattedResume->getClientOriginalExtension();
                    $fileFormattedResumeRealPath = $fileFormattedResume->getRealPath();
                    $fileFormattedResumeSize = $fileFormattedResume->getSize();
                    $fileFormattedResumeMimeType = $fileFormattedResume->getMimeType();

                    $extention = File::extension($fileFormattedResumeName);

                    $fileFormattedResumeNameArray = explode('.',$fileFormattedResumeName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileFormattedResumeNameArray[0]);
                    $fileFormattedResumeNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileFormattedResume->move($dir,$fileFormattedResumeNewName);

                    $fileFormattedResumeNewPath = $dir.$fileFormattedResumeNewName;

                    $candidateFormattedFileUpload = new CandidateUploadedResume();
                    $candidateFormattedFileUpload->candidate_id = $candidate_id;
                    $candidateFormattedFileUpload->uploaded_by = $user_id;
                    $candidateFormattedFileUpload->file_name = $fileFormattedResumeNewName;
                    $candidateFormattedFileUpload->file_type = 'Candidate Formatted Resume';
                    $candidateFormattedFileUpload->file = $fileFormattedResumeNewPath;
                    $candidateFormattedFileUpload->mime = $fileFormattedResumeMimeType;
                    $candidateFormattedFileUpload->size = $fileFormattedResumeSize;
                    $candidateFormattedFileUpload->uploaded_date = date('Y-m-d');
                    $candidateFormattedFileUploadStored = $candidateFormattedFileUpload->save();

                }

                if(isset($fileCoverLatter) && $fileCoverLatter->isValid()){

                    $fileCoverLatterName = $fileCoverLatter->getClientOriginalName();
                    $fileCoverLatterExtention = $fileCoverLatter->getClientOriginalExtension();
                    $fileCoverLatterRealPath = $fileCoverLatter->getRealPath();
                    $fileCoverLatterSize = $fileCoverLatter->getSize();
                    $fileCoverLatterMimeType = $fileCoverLatter->getMimeType();

                    $extention = File::extension($fileCoverLatterName);

                    $fileCoverLatterNameArray = explode('.',$fileCoverLatterName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileCoverLatterNameArray[0]);
                    $fileCoverLatterNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileCoverLatter->move($dir,$fileCoverLatterNewName);

                    $fileCoverLatterNewPath = $dir.$fileCoverLatterNewName;

                    $candidateCoverLatterUpload = new CandidateUploadedResume();
                    $candidateCoverLatterUpload->candidate_id = $candidate_id;
                    $candidateCoverLatterUpload->uploaded_by = $user_id;
                    $candidateCoverLatterUpload->file_name = $fileCoverLatterNewName;
                    $candidateCoverLatterUpload->file_type = 'Candidate Cover Latter';
                    $candidateCoverLatterUpload->file = $fileCoverLatterNewPath;
                    $candidateCoverLatterUpload->mime = $fileCoverLatterMimeType;
                    $candidateCoverLatterUpload->size = $fileCoverLatterSize;
                    $candidateCoverLatterUpload->uploaded_date = date('Y-m-d');
                    $candidateCoverLatterUploadStored = $candidateCoverLatterUpload->save();

                }

                if(isset($fileOthers) && $fileOthers->isValid()){

                    $fileOthersName = $fileOthers->getClientOriginalName();
                    $fileOthersExtention = $fileOthers->getClientOriginalExtension();
                    $fileOthersRealPath = $fileOthers->getRealPath();
                    $fileOthersSize = $fileOthers->getSize();
                    $fileOthersMimeType = $fileOthers->getMimeType();

                    $extention = File::extension($fileOthersName);

                    $fileOthersNameArray = explode('.',$fileOthersName);

                    $dir = 'uploads/candidate/'.$candidate_id.'/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $temp_file_name = trim($fileOthersNameArray[0]);
                    $fileOthersNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
                    $fileOthers->move($dir,$fileOthersNewName);

                    $fileOthersNewPath = $dir.$fileOthersNewName;

                    $candidateOthersUpload = new CandidateUploadedResume();
                    $candidateOthersUpload->candidate_id = $candidate_id;
                    $candidateOthersUpload->uploaded_by = $user_id;
                    $candidateOthersUpload->file_name = $fileOthersNewName;
                    $candidateOthersUpload->file_type = 'Others';
                    $candidateOthersUpload->file = $fileOthersNewPath;
                    $candidateOthersUpload->mime = $fileOthersMimeType;
                    $candidateOthersUpload->size = $fileOthersSize;
                    $candidateOthersUpload->uploaded_date = date('Y-m-d');
                    $candidateOthersUploadStored = $candidateOthersUpload->save();

                }

                if(isset($job_id) && $job_id>0){
                    $job_id = $request->input('jobopen');
                    $status_id = env('associate_candidate_status', 1);

                    $jobopening = new JobAssociateCandidates();
                    $jobopening->job_id = $job_id;
                    $jobopening->candidate_id = $candidate_id;
                    $jobopening->status_id = $status_id;
                    $jobopening->created_at = time();
                    $jobopening->updated_at = time();
                    $jobopening->shortlisted = 0;
                    $jobopening->associate_by = $user_id;
                    $jobopening->date = date("Y-m-d h:i:s");
                    $jobopening->save();
                }

            }
        }

        if(isset($job_id) && $job_id>0){
            // Candidate Vacancy Details email
            $candidate_vacancy_details = CandidateBasicInfo::candidateAssociatedEmail($candidate_id,$user_id,$job_id);
        }
    
        return redirect()->route('candidate.index')->with('success','Candidate Created Successfully');

    }

    public function edit($id){
        $candidates = CandidateBasicInfo::leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id')
            ->leftjoin('candidate_uploaded_resume','candidate_uploaded_resume.candidate_id','=','candidate_basicinfo.id')
            ->select('candidate_basicinfo.id as id', 'candidate_basicinfo.type as candidateSex', 'candidate_basicinfo.marital_status as maritalStatus',
                'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.mobile as mobile', 'candidate_basicinfo.phone as phone',
                'candidate_basicinfo.fax as fax', 'candidate_basicinfo.email as email',
                'candidate_basicinfo.country as country', 'candidate_basicinfo.state as state',
                'candidate_basicinfo.city as city', 'candidate_basicinfo.street1 as street1',
                'candidate_basicinfo.street2 as street2', 'candidate_basicinfo.zipcode as zipcode',
                'candidate_otherinfo.highest_qualification as highest_qualification', 'candidate_otherinfo.experience_years as experience_years',
                'candidate_otherinfo.experience_months as experience_months', 'candidate_otherinfo.current_job_title as current_job_title',
                'candidate_otherinfo.current_employer as current_employer', 'candidate_otherinfo.expected_salary as expected_salary',
                'candidate_otherinfo.current_salary as current_salary', 'candidate_otherinfo.skill as skill',
                'candidate_otherinfo.skype_id as skype_id', 'candidate_otherinfo.status_id as candidateStatus',
                'candidate_otherinfo.source_id as candidateSource', 'candidate_uploaded_resume.file_name as resume')
            ->where('candidate_basicinfo.id',$id)
            ->where('candidate_otherinfo.deleted_at',null)
            ->where('candidate_uploaded_resume.deleted_at',null)
            ->first();

        $candidateSex = CandidateBasicInfo::getTypeArray();
        $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();
        $candidateSource = CandidateBasicInfo::getCandidateSourceArray();
        $candidateStatus = CandidateBasicInfo::getCandidateStatusArray();

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title'].",".$v['location'];
        }


        // check if candidate associate with any job
        $job_id = JobAssociateCandidates::getAssociatedJobIdByCandidateId($id);

        $candidateModel = new CandidateBasicInfo();
        $candidate_upload_type = $candidateModel->candidate_upload_type;

        $i = 0;
        $candidateDetails = array();
        $candidateFiles = CandidateUploadedResume::join('users','users.id','=','candidate_uploaded_resume.uploaded_by')
            ->select('candidate_uploaded_resume.*', 'users.name as upload_name')
            ->where('candidate_uploaded_resume.candidate_id',$id)
            ->get();
        $utils = new Utils();
        if(isset($candidateFiles) && sizeof($candidateFiles) > 0){
            foreach ($candidateFiles as $candidateFile) {
                $candidateDetails[$i]['id'] = $candidateFile->id;
                $candidateDetails[$i]['fileName'] = $candidateFile->file_name;
                $candidateDetails[$i]['url'] = "../../".$candidateFile->file;
                $candidateDetails[$i]['category'] = $candidateFile->file_type;
                $candidateDetails[$i]['uploaded_by'] = $candidateFile->upload_name ;
                $candidateDetails[$i]['size'] = $utils->formatSizeUnits($candidateFile->size);

                if (array_search($candidateFile->file_type, $candidate_upload_type)) {
                    unset($candidate_upload_type[array_search($candidateFile->file_type, $candidate_upload_type)]);
                }
                $i++;
            }
        }
        $candidate_upload_type['Others'] = 'Others';

        $viewVariable = array();
        $viewVariable['candidateSex'] = $candidateSex;
        $viewVariable['maritalStatus'] = $maritalStatus;
        $viewVariable['candidateSource'] = $candidateSource;
        $viewVariable['candidateStatus'] = $candidateStatus;
        //$viewVariable['emailDisabled'] = 'disabled';
        $viewVariable['candidate'] = $candidates;
        $viewVariable['jobopen'] = $jobopen;
        $viewVariable['action'] = 'edit';
        $viewVariable['job_id'] = $job_id;
        $viewVariable['candidateDetails'] = $candidateDetails;
        $viewVariable['candidate_upload_type'] = $candidate_upload_type;

        //print_r($viewVariable);exit;
        return view('adminlte::candidate.edit',$viewVariable);
    }

    public function update(Request $request, $id){

        $this->validate($request, [
           // 'candidateSex' => 'required',
            'fname' => 'required',
       //     'lname' => 'required',
//            'email' => 'unique:candidate_basicinfo,email',
            'mobile'  => 'required',
            'email' => 'required'
        ]);

        $user_id = \Auth::user()->id;

        // for redirecting to candidate associating page after updating candidate info

        $candidateSex = $request->input('candidateSex');
        $candiateMaritalStatus = $request->input('maritalStatus');
        $candiateFname = $request->input('fname');
       // $candiateLname = $request->input('lname');
        $candiateMobile = $request->input('mobile');
        $candiatePhone = $request->input('phone');
        $candiateFAX = $request->input('fax');
        $candiateStreet1 = $request->input('street1');
        $candiateStreet2 = $request->input('street2');
        $candiateCity = $request->input('city');
        $candiateState = $request->input('state');
        $candiateCountry = $request->input('country');
        $candiateZipCode = $request->input('zipcode');
        $candidateEmail = $request->input('email');

        $candiateHighest_qualification = $request->input('highest_qualification');
        $candiateExperience_years = $request->input('experience_years');
        $candiateExperience_months = $request->input('experience_months');
        $candiateCurrent_job_title = $request->input('current_job_title');
        $candiateCurrent_employer = $request->input('current_employer');
        $candiateExpected_salary = $request->input('expected_salary');
        $candiateCurrent_salary = $request->input('current_salary');
        $candiateSkill = $request->input('skill');
        $candiateSkype_id = $request->input('skype_id');
        $candiateStatus = $request->input('candidateStatus');
        $candidateSource = $request->input('candidateSource');

        $candidate = CandidateBasicInfo::find($id);
        if(isset($candidate) && sizeof($candidate) > 0){
            if(isset($candidateEmail)){
                $candidate->email = $candidateEmail;
            }
            if(isset($candidateSex)){
                $candidate->type = $candidateSex;
            }
            if(isset($candiateMaritalStatus)){
                $candidate->marital_status = $candiateMaritalStatus;
            }
            if(isset($candiateFname)){
                $candidate->full_name = $candiateFname;
            }
            /*if(isset($candiateFname)){
                $candidate->lname = $candiateLname;
            }*/
            if(isset($candiateMobile)){
                $candidate->mobile = $candiateMobile;
            }
            if(isset($candiatePhone)){
                $candidate->phone = $candiatePhone;
            }
            if(isset($candiateFAX)){
                $candidate->fax = $candiateFAX;
            }
            if(isset($candiateStreet1)){
                $candidate->street1 = $candiateStreet1;
            }
            if(isset($candiateStreet2)){
                $candidate->street2 = $candiateStreet2;
            }
            if(isset($candiateCity)){
                $candidate->city = $candiateCity;
            }
            if(isset($candiateState)){
                $candidate->state = $candiateState;
            }
            if(isset($candiateCountry)){
                $candidate->country = $candiateCountry;
            }
            if(isset($candiateZipCode)){
                $candidate->zipcode = $candiateZipCode;
            }
             

            $validator = \Validator::make(Input::all(),$candidate::$rules);

            if($validator->fails()){
                return redirect('candidate/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
            }

            $candidateUpdated = $candidate->save();

            if($candidateUpdated){
                $candidate_id = $candidate->id;

                $candidateOtherInfo = CandidateOtherInfo::where('candidate_id',$candidate_id)->first();
                if(!isset($candidateOtherInfo) && sizeof($candidateOtherInfo) == 0){
                    $candidateOtherInfo = new CandidateOtherInfo();
                }
                $candidateOtherInfo->candidate_id = $candidate_id;

                if(isset($candiateHighest_qualification)){
                    $candidateOtherInfo->highest_qualification = $candiateHighest_qualification;
                }
                if(isset($candiateExperience_years) && $candiateExperience_years!=''){
                    $candidateOtherInfo->experience_years = $candiateExperience_years;
                }
                if(isset($candiateExperience_months) && $candiateExperience_months!=''){
                    $candidateOtherInfo->experience_months = $candiateExperience_months;
                }
                if(isset($candiateCurrent_job_title)){
                    $candidateOtherInfo->current_job_title = $candiateCurrent_job_title;
                }
                if(isset($candiateCurrent_employer)){
                    $candidateOtherInfo->current_employer = $candiateCurrent_employer;
                }
                if(isset($candiateExpected_salary) && $candiateExpected_salary!=''){
                    $candidateOtherInfo->expected_salary = $candiateExpected_salary;
                }
                if(isset($candiateCurrent_salary) && $candiateCurrent_salary!=''){
                    $candidateOtherInfo->current_salary = $candiateCurrent_salary;
                }
                if(isset($candiateSkill)){
                    $candidateOtherInfo->skill = $candiateSkill;
                }
                if(isset($candiateSkype_id)){
                    $candidateOtherInfo->skype_id = $candiateSkype_id;
                }
                if(isset($candiateStatus) && $candiateStatus!=''){
                    $candidateOtherInfo->status_id = $candiateStatus;
                }
                if(isset($candidateSource)){
                    $candidateOtherInfo->source_id = $candidateSource;
                }
                
                /*if(isset($user_id)){
                    $candidate->owner_id = $user_id;
                }*/
                $candidateOtherInfoUpdated = $candidateOtherInfo->save();

                if($candidateOtherInfoUpdated){
                    $candidate_upload_type = $request->candidate_upload_type;
                    $file = $request->file('file');
                    if (isset($file) && $file->isValid()) {
                        $fileName = $file->getClientOriginalName();
                        $fileExtention = $file->getClientOriginalExtension();
                        $fileRealPath = $file->getRealPath();
                        $fileSize = $file->getSize();
                        $fileMimeType = $file->getMimeType();

                        $extention = File::extension($fileName);

                        $fileNameArray = explode('.',$fileName);

                        $dir = 'uploads/candidate/'.$candidate_id.'/';

                        if (!file_exists($dir) && !is_dir($dir)) {

                            mkdir($dir, 0777, true);
                            chmod($dir, 0777);
                        }
                        $temp_file_name = trim($fileNameArray[0]);
                        $fileNewName = $temp_file_name.date('ymdhhmss').'.'.$extention;
                        $file->move($dir,$fileNewName);

                        $fileNewPath = $dir.$fileNewName;

                        $candidateFileUpload = new CandidateUploadedResume();
                        $candidateFileUpload->candidate_id = $candidate_id;
                        $candidateFileUpload->uploaded_by = $user_id;
                        $candidateFileUpload->file_name = $fileNewName;
                        $candidateFileUpload->file_type = $candidate_upload_type;
                        $candidateFileUpload->file = $fileNewPath;
                        $candidateFileUpload->mime = $fileMimeType;
                        $candidateFileUpload->uploaded_date = date('Y-m-d');
                        $candidateFileUploadUpdated = $candidateFileUpload->save();

                        //return redirect('candidate/'.$candidate_id.'/edit');

                        return redirect()->route('candidate.edit',[$candidate_id])->with('success','Attachment Uploaded Successfully.');
                    }
                }
                $job_id = $request->input('jobopen');
                JobAssociateCandidates::where('job_id',$job_id)->where('candidate_id',$candidate_id)->delete();
                if(isset($job_id) && $job_id>0){
                    $job_id = $request->input('jobopen');
                    $status_id = env('associate_candidate_status', 1);

                    $jobopening = new JobAssociateCandidates();
                    $jobopening->job_id = $job_id;
                    $jobopening->candidate_id = $candidate_id;
                    $jobopening->status_id = $status_id;
                    $jobopening->created_at = time();
                    $jobopening->updated_at = time();
                    $jobopening->shortlisted = 0;
                    $jobopening->associate_by = $user_id;
                    $jobopening->date = date("Y-m-d h:i:s");
                    $jobopening->save();
                }
            } 

            else {
                return redirect('candiate/')->with('error','Something went wrong while updating');
            }
            return redirect()->route('candidate.index')->with('success','Candidate Updated Successfully');

        } else {
            return redirect('candiate/')->with('error','No Candiate found');
        }
    }

    public function show($id){
//print_r($id);exit;
        $candidates = CandidateBasicInfo::leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id')
            ->leftjoin('candidate_uploaded_resume','candidate_uploaded_resume.candidate_id','=','candidate_basicinfo.id')
            ->leftjoin('candidate_source','candidate_source.id','=','candidate_otherinfo.source_id')
            ->leftjoin('candidate_status','candidate_status.id','=','candidate_otherinfo.status_id')
            ->leftjoin('users','users.id','=','candidate_uploaded_resume.uploaded_by')
            ->select('candidate_basicinfo.id as id', 'candidate_basicinfo.type as candidateSex', 'candidate_basicinfo.marital_status as maritalStatus',
                'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.mobile as mobile', 'candidate_basicinfo.phone as phone',
                'candidate_basicinfo.fax as fax', 'candidate_basicinfo.email as email',
                'candidate_basicinfo.country as country', 'candidate_basicinfo.state as state',
                'candidate_basicinfo.city as city', 'candidate_basicinfo.street1 as street1',
                'candidate_basicinfo.street2 as street2', 'candidate_basicinfo.zipcode as zipcode',
                'candidate_otherinfo.highest_qualification as highest_qualification', 'candidate_otherinfo.experience_years as experience_years',
                'candidate_otherinfo.experience_months as experience_months', 'candidate_otherinfo.current_job_title as current_job_title',
                'candidate_otherinfo.current_employer as current_employer', 'candidate_otherinfo.expected_salary as expected_salary',
                'candidate_otherinfo.current_salary as current_salary', 'candidate_otherinfo.skill as skill',
                'candidate_otherinfo.skype_id as skype_id', 'candidate_otherinfo.status_id as candidateStatus',
                'candidate_otherinfo.source_id as candidateSource', 'candidate_uploaded_resume.file_name as resume',
                'candidate_uploaded_resume.file as file', 'users.name as uploaded_by',
                'candidate_status.name as candidate_status_name', 'candidate_source.name as candidate_source_name')
            ->where('candidate_basicinfo.id',$id)
            ->where('candidate_otherinfo.deleted_at',null)
            ->where('candidate_uploaded_resume.deleted_at',null)
            ->first();
//print_r($candidates);exit;
        $candidateDetails = array();

        $candidateModel = new CandidateBasicInfo();
        $candidate_upload_type = $candidateModel->candidate_upload_type;

        if(isset($candidates) && sizeof($candidates) > 0){

            $candidateDetails['id'] = $candidates->id;
            $candidateDetails['fname'] = $candidates->fname;
           // $candidateDetails['lname'] = $candidates->lname;
            $candidateDetails['email'] = $candidates->email;
            $candidateDetails['candidateSex'] = $candidates->candidateSex;
            $candidateDetails['maritalStatus'] = $candidates->maritalStatus;
            $candidateDetails['mobile'] = $candidates->mobile;
            $candidateDetails['phone'] = $candidates->phone;
            $candidateDetails['jobopen'] = $candidates->jobopen;
          //  $candidateDetails['fax'] = $candidates->fax;
            $candidateDetails['country'] = $candidates->country;
            $candidateDetails['state'] = $candidates->state;
            $candidateDetails['city'] = $candidates->city;
            $candidateDetails['street1'] = $candidates->street1;
            $candidateDetails['street2'] = $candidates->street2;
            $candidateDetails['zipcode'] = $candidates->zipcode;
            $candidateDetails['highest_qualification'] = $candidates->highest_qualification;
            $candidateDetails['experience_years'] = $candidates->experience_years;
            $candidateDetails['experience_months'] = $candidates->experience_months;
            $candidateDetails['current_job_title'] = $candidates->current_job_title;
            $candidateDetails['current_employer'] = $candidates->current_employer;
            $candidateDetails['expected_salary'] = $candidates->expected_salary;
            $candidateDetails['current_salary'] = $candidates->current_salary;
            $candidateDetails['skill'] = $candidates->skill;
            $candidateDetails['skype_id'] = $candidates->skype_id;
            $candidateDetails['resume'] = $candidates->resume;
            $candidateDetails['candidate_status'] = $candidates->candidate_status_name;
            $candidateDetails['candidate_source'] = $candidates->candidate_source_name;
            $candidateDetails['fileUrl'] = $candidates->file;
            $candidateDetails['uploaded_by'] = $candidates->uploaded_by;


            $i = 0;
            $candidateDetails['files'] = array();
            $candidateFiles = CandidateUploadedResume::join('users','users.id','=','candidate_uploaded_resume.uploaded_by')
                ->select('candidate_uploaded_resume.*', 'users.name as upload_name')
                ->where('candidate_uploaded_resume.candidate_id',$id)
                ->get();
            $utils = new Utils();
            if(isset($candidateFiles) && sizeof($candidateFiles) > 0){
                foreach ($candidateFiles as $candidateFile) {
                    $candidateDetails['files'][$i]['id'] = $candidateFile->id;
                    $candidateDetails['files'][$i]['fileName'] = $candidateFile->file_name;
                    $candidateDetails['files'][$i]['url'] = "../../".$candidateFile->file;
                    $candidateDetails['files'][$i]['category'] = $candidateFile->file_type;
                    $candidateDetails['files'][$i]['uploaded_by'] = $candidateFile->upload_name ;
                    $candidateDetails['files'][$i]['size'] = $utils->formatSizeUnits($candidateFile->size);

                    if (array_search($candidateFile->file_type, $candidate_upload_type)) {
                        unset($candidate_upload_type[array_search($candidateFile->file_type, $candidate_upload_type)]);
                    }

                    $i++;

                }
            }

            $candidateDetails['job'] = array();
            $i = 0;

            $candidateJob = JobAssociateCandidates::join('job_openings','job_openings.id','=','job_associate_candidates.job_id')
                                ->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id')
                                ->join('users','users.id','=','job_openings.hiring_manager_id')
                                ->select('job_openings.posting_title as posting_title','client_basicinfo.name as company_name','job_openings.city as city','job_openings.state as state','job_openings.country as country','users.name as managed_by','job_associate_candidates.created_at as date')
                                ->where('job_associate_candidates.candidate_id',$id)
                                ->get();


            if (isset($candidateJob) && sizeof($candidateJob) > 0) {
                foreach ($candidateJob as $candidateJobs) {
                    $candidateDetails['job'][$i]['posting_title'] = $candidateJobs->posting_title;
                    $candidateDetails['job'][$i]['company_name'] = $candidateJobs->company_name;

                    $location ='';
                    if($candidateJobs->city!=''){
                        $location .= $candidateJobs->city;
                    }
                    if($candidateJobs->state!=''){
                        if($location=='')
                            $location .= $candidateJobs->state;
                        else
                            $location .= ", ".$candidateJobs->state;
                    }
                    if($candidateJobs->country!=''){
                        if($location=='')
                            $location .= $candidateJobs->country;
                        else
                            $location .= ", ".$candidateJobs->country;
                    }
                    $date_time = strtotime($candidateJobs->date);
                    date_default_timezone_set("Asia/kolkata");
                    $candidateDetails['job'][$i]['location'] = $location;
                    $candidateDetails['job'][$i]['managed_by'] = $candidateJobs->managed_by;
                    $candidateDetails['job'][$i]['datetime'] = date('d-m-Y h:i A',$date_time);

                   $i++; 
                }
            }
        }

        $candidate_upload_type['Others'] = 'Others';

        $viewVariable = array();
        $viewVariable['candidateDetails'] = $candidateDetails;
        $viewVariable['candidate_upload_type'] = $candidate_upload_type;
        $viewVariable['action'] = 'show';

//        print_r($candidates);exit;

        return view('adminlte::candidate.show',$viewVariable);
    }

    public function destroy($id){

        $form_name = $_POST['form_name'];

        $res = CandidateBasicInfo::CheckAssociation($id);

        if($res){
            $candidateUplodedDocDel = CandidateUploadedResume::where('candidate_id',$id)->delete();
            $candidateOtherInfoDel = CandidateOtherInfo::where('candidate_id',$id)->delete();
            $candidateBasicInfoDel = CandidateBasicInfo::where('id',$id)->delete();

            if(isset($form_name) && $form_name == 'applicant'){

                return redirect()->route('applicant.candidate')->with('success','Candidate Deleted Successfully.');
            }
            else{

                return redirect()->route('candidate.index')->with('success','Candidate Deleted Successfully.');
            }
        }
        else{
            return redirect()->route('candidate.index')->with('error','Candidate is associated with job.!!');
        }
        //return redirect()->route('candidate.index');
    }

    public function upload(Request $request){

        // For Applicant candidate
        $form_name = $request->form_name;

        $candidate_upload_type = $request->candidate_upload_type;
        $file = $request->file('file');
        $candidate_id = $request->id;

        $user_id = \Auth::user()->id;

        if(isset($file) && $file->isValid()){
            $fileName = $file->getClientOriginalName();
            $fileExtention = $file->getClientOriginalExtension();
            $fileRealPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $fileMimeType = $file->getMimeType();

            $extention = File::extension($fileName);

            $fileNameArray = explode('.',$fileName);

            $dir = 'uploads/candidate/'.$candidate_id.'/';

            if (!file_exists($dir) && !is_dir($dir)) {

                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $temp_file_name = trim($fileNameArray[0]);
            $fileNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
            $file->move($dir,$fileNewName);

            $fileNewPath = $dir.$fileNewName;

            $candidateFileUpload = new CandidateUploadedResume();
            $candidateFileUpload->candidate_id = $candidate_id;
            $candidateFileUpload->uploaded_by = $user_id;
            $candidateFileUpload->file_name = $fileNewName;
            $candidateFileUpload->file_type = $candidate_upload_type;
            $candidateFileUpload->file = $fileNewPath;
            $candidateFileUpload->mime = $fileMimeType;
            $candidateFileUpload->size = $fileSize;
            $candidateFileUpload->uploaded_date = date('Y-m-d');
            $candidateFileUploadStored = $candidateFileUpload->save();
        }

        if(isset($form_name) && $form_name == 'applicantShow'){
            return redirect()->route('applicant-candidate.show',[$candidate_id])->with('success','Attachment Uploaded Successfully.');
        }
        else{
            return redirect()->route('candidate.show',[$candidate_id])->with('success','Attachment Uploaded Successfully.');
        }
    }

    public function attachmentsDestroy($fileId){

        $candiateFileDetails = CandidateUploadedResume::find($fileId);

        unlink($candiateFileDetails->file);

        $candidateFileDelete = CandidateUploadedResume::where('id',$fileId)->delete();

        $candidateId = $_POST['id'];

        if (isset($_POST['edit']) && $_POST['edit'] != '') {

            $action = $_POST['edit'];
            return redirect()->route('candidate.edit',[$candidateId])->with('success','Attachment Deleted Successfully.');
        }

        if (isset($_POST['applicant_name']) && $_POST['applicant_name'] == 'applicantShow'){
            return redirect()->route('applicant-candidate.show',[$candidateId])->with('success','Attachment Deleted Successfully.');
        }

        if (isset($_POST['applicant_name']) && $_POST['applicant_name'] == 'applicantEdit'){
            return redirect()->route('applicant-candidate.edit',[$candidateId])->with('success','Attachment Deleted Successfully.');
        }

        return redirect()->route('candidate.show',[$candidateId])->with('success','Attachment deleted Successfully');
    }

    public function getCandidateInfo($id){
        $candidate = CandidateBasicInfo::find($id);

        $response['returnvalue'] = 'invalid';
        if(isset($candidate->phone)) {
            $response['phone'] = $candidate->phone;
            $response['returnvalue'] = 'valid';
        }
        if(isset($candidate->mobile)){
            $response['mobile'] = $candidate->mobile;
            $response['returnvalue'] = 'valid';
        }

        return json_encode($response);exit;
    }

    public function extractResume(){
        return view('adminlte::candidate.extractResume');
    }

    public function extractResumeStore(Request $request){

        $file = $request->file('file');
        $user_id = \Auth::user()->id;

        if(isset($file) && $file->isValid()){
            $fileName = $file->getClientOriginalName();
            $fileExtention = $file->getClientOriginalExtension();
            $fileRealPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $fileMimeType = $file->getMimeType();
//            print_r($fileExtention);exit;

            $extention = File::extension($fileName);

            $fileNameArray = explode('.',$fileName);

            $dir = 'uploads/resume/';

            if (!file_exists($dir) && !is_dir($dir)) {

                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $glob = glob('uploads/resume/*.*');
            $glob = sprintf('%02d', count($glob) + 1);
            $fileNewName = $glob.'-'.$fileName;
            $file->move($dir,$fileNewName);
            $fileNewPath = $dir.$fileNewName;

            if ($fileExtention == 'pdf') {
                $pdfObj = new pdfParser();
                $resumeText = $pdfObj->parseFile($fileNewPath);
                // $resumeText = $pdfObj->getText();
            } else {
                $docObj = new docParser($fileNewPath);
                $resumeText = $docObj->convertToText();
            }

            $fileInfo = explode(PHP_EOL, $resumeText);

            $records = [];

            foreach ($fileInfo as $row) {
                // if($row == '') continue;
                // $parts = explode(',12', $row);
                $parts = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $row);

                foreach ($parts as $part) {
                    if ($part == '') {
                        continue;
                    }

                    // echo $part.'<br><br>';
                    $part = strtolower($part);

//                    print_r(strpos($part,'name'));exit;
                    //  ***************  EMAIL  **************

                    if (strpos($part, '@') || strpos($part, 'mail')) {
                        $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
                        preg_match_all($pattern, $part, $matches);

                        foreach ($matches[0] as $match) {
                            $records['email'] = $match;
                        }
//                        print_r($part);exit;
                    }


                    //  ***************  MOBILE  **************

                    preg_match_all('/\d{10}/', $part, $matches);
                    if (count($matches[0])) {
                        foreach ($matches[0] as $mob) {
                            $records['mobile'] = $mob;
                        }
                    }

                    preg_match_all('/\d{12}/', $part, $matches);
                    if (count($matches[0])) {
                        foreach ($matches[0] as $mob) {
                            $records['mobile'] = $mob;
                        }
                    }

                    preg_match_all('/(\d{5}) (\d{5})/', $part, $matches);
                    if (count($matches[0])) {
                        foreach ($matches[0] as $mob) {
                            $records['mobile'] = $mob;
                        }
                    }

                    //  ***************  NAME  **fe************
//                    print_r($part);exit;


                    if (preg_match('/name/', $part)) {
                        $name = preg_split('/:|-/', $part);

//                        print_r($name);exit;

                        if(sizeof($name) > 1){
                            $nameArr = explode(' ',$name[1]);
                        } else {
                            $nameArr = explode(' ',$name[0]);
                        }

                        foreach($nameArr as $item){
                            if($item != null && $item != 'name'){
                                $names[] = $item;
                            }
                        }

                        $fname = $names[0];
                        $lname = $names[1];

                        $records['fname'] = $fname;
                        $records['lname'] = $lname;

                    } else {

                        if (isset($records['email'])) {
                            $email = $records['email'];
                            $e = explode('@', $email);
                            $records['name']= $e[0];
                            if(!isset($records['fname']) || $records['fname']==''){
                                $records['fname'] = $e[0];
                                //$records['lname'] = $e[0];
                            }
                            if(!isset($records['lname']) || $records['lname']==''){
                                $records['lname'] = $e[0];
                                //$records['lname'] = $e[0];
                            }
                            /*foreach ($records['email'] as $email) {
                                $e = explode('@', $email);
                                $records['name']= $e[0];
                                // code...
                            }*/
                        }
                    }



                    if (preg_match('/phone/', $part)) {

                        $phone = preg_split('/:|-/', $part);

                        $records['phone'] = $phone[1];
                    } else {
                        $records['phone'] = null;
                    }

                    if (preg_match('/fax/', $part)) {

                        $fax = preg_split('/:|-/', $part);
                        $records['fax'] = $fax[1];
                    } else {
                        $records['fax'] = null;
                    }

                    if (preg_match('/sex | gender/', $part)) {

                        $sex = preg_split('/:|-/', $part);
                        if(isset($sex[1]) && ($sex[1] == 'male' || $sex[1] == 'm')){
                            $records['sex'] = 'Male';
                        } else {
                            $records['sex'] = 'Female';
                        }
                    } else {
                        $records['sex'] = null;
                    }

                    if (preg_match('/marital status/', $part)) {
                        $marital_status = preg_split('/:_|-/', $part);

                        if(sizeof($marital_status) > 1){
                            if($marital_status[1] == 'Single' || $marital_status[1] == 'single'){
                                $records['marital_status'] = 'Single';
                            } elseif($marital_status[1] == 'Engaged' || $marital_status[1] == 'engaged') {
                                $records['marital_status'] = 'Engaged';
                            } elseif($marital_status[1] == 'Married' || $marital_status[1] == 'married') {
                                $records['marital_status'] = 'Married';
                            }
                        } else {
                            if($marital_status[0] == 'Single' || $marital_status[0] == 'single'){
                                $records['marital_status'] = 'Single';
                            } elseif($marital_status[0] == 'Engaged' || $marital_status[0] == 'engaged') {
                                $records['marital_status'] = 'Engaged';
                            } elseif($marital_status[0] == 'Married' || $marital_status[0] == 'married') {
                                $records['marital_status'] = 'Married';
                            }
                        }
                    } else {
                        $records['marital_status'] = null;
                    }
                }
            }

            foreach ($records as $key => $value) {
                $records[$key] = $value;
            }
            if(!isset($records['sex']) || $records['sex']==''){
                $records['sex'] = 'Male';
            }
            if(!isset($records['marital_status']) || $records['marital_status']==''){
                $records['marital_status'] = 'Single';
            }
            //print_r($records);exit;
            if(isset($records) && sizeof($records) > 0){
                if(isset($records['fname']) && $records['fname']!= ''
                    && isset($records['lname']) && $records['lname']!=''
                    && isset($records['mobile']) && $records['mobile']!='' ){
                    $candidateBasicInfo = new CandidateBasicInfo();
                    $candidateBasicInfo->fname = $records['fname'];
                    $candidateBasicInfo->lname = $records['lname'];
                    $candidateBasicInfo->email = $records['email'];
                    $candidateBasicInfo->type =  $records['sex'];
                    $candidateBasicInfo->marital_status =  $records['marital_status'];
                    $candidateBasicInfo->mobile = $records['mobile'];
                    $candidateBasicInfo->phone = $records['phone'];
                    $candidateBasicInfo->fax = $records['fax'];

                    //print_r($candidateBasicInfo);exit;
                    $candidateBasicInfoStored = $candidateBasicInfo->save();

                    $candidateId = $candidateBasicInfo->id;

                    if(isset($candidateId)){

                        $fileName = $file->getClientOriginalName();
                        $fileExtention = $file->getClientOriginalExtension();
                        $fileRealPath = $file->getRealPath();
//                    $fileSize = $file->getSize();
//                    $fileMimeType = $file->getMimeType();

                        $extention = File::extension($fileName);

                        $fileNameArray = explode('.',$fileName);

                        $dirNew = 'uploads/candidate/'.$candidateId.'/';

                        if (!file_exists($dirNew) && !is_dir($dirNew)) {

                            mkdir($dirNew, 0777, true);
                            chmod($dirNew, 0777);
                        }
                        $temp_file_name = trim($fileNameArray[0]);
                        $fileNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
//                    $file->move($dirNew,$fileNewName);
//                    $move = File::move($fileNewPath)
                        rename($fileNewPath,$dirNew.$fileNewName);

                        $fileRenamedNewPath = $dirNew.$fileNewName;

                        $candidateFileUpload = new CandidateUploadedResume();
                        $candidateFileUpload->candidate_id = $candidateId;
                        $candidateFileUpload->uploaded_by = $user_id;
                        $candidateFileUpload->file_name = $fileNewName;
                        $candidateFileUpload->file_type = 'Candidate Resume';
                        $candidateFileUpload->file = $fileRenamedNewPath;
                        $candidateFileUpload->mime = $fileMimeType;
                        $candidateFileUpload->size = $fileSize;
                        $candidateFileUpload->uploaded_date = date('Y-m-d');
                        $candidateFileUploadStored = $candidateFileUpload->save();


                        @unlink($fileNewPath);
                    }

                    return redirect('candidate/'.$candidateId.'/show');
                }
                else {
                    return redirect('candidate/resume')->with('error','Sorry, not able to fetch data from file');
                }

            } else {
                return redirect('candidate/resume')->with('error','Sorry, there was an error uploading your file');
            }
        } else {
            return redirect('candidate/resume')->with('error','Sorry, invalid file type.');
        }
//        return view('adminlte::candidate.extractResume');
    }

    public function importExport(){

        $candidateSource = CandidateBasicInfo::getCandidateimportsource();

        return view('adminlte::candidate.import',compact('candidateSource'));
    }

    public function importn1excel(){

        if($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();

            $data = Excel::load($path, function ($reader) {})->get();

            $messages = array();

            if (!empty($data) && $data->count()) {

                foreach ($data->toArray() as $key => $value) {

                    if(!empty($value)) {
                        //foreach ($value as $v) {
                           //print_r($value);exit;

                            //$sr_no = $v['sr_no'];
                          //  $managed_by = $v['managed_by'];
                            $name = $value['name'];
                            $gender = $value['gender'];
                            $marital_status =$value['marital_status'];
                            $mobile_number = $value['phone_number'];
                            $email = $value['email_id'];
                            $current_employer = $value['curr_company_name'];
                            $experience = $value['total_experience'];
                            $job_title = $value['curr_company_designation'];
                            $skill = $value['key_skills'];
                            $city = $value['home_towncity'];
                            $zipcode = $value['pin_code'];
                            $address = $value['permanent_address'];
                            $current_salary = $value['annual_salary'];

                            // first check email already exist or not , if exist doesnot update data
                            $candidate_cnt = CandidateBasicInfo::checkCandidateByEmail($email);

                            if($candidate_cnt>0){
                                $messages[] = "Record $name already present ";
                            }
                            else{
                                   // $namearray = explode(' ', $name);
                                    $mobilearray = explode(',', $mobile_number);
                                    $addressarray = explode(',', $address);

                                    // Insert new candidate
                                    $candidate_basic_info = new CandidateBasicInfo();
                                    $candidate_basic_info->full_name = $name;
                                    //$candidate_basic_info->lname = $namearray[1];
                                    $candidate_basic_info->email = $email;
                                    $candidate_basic_info->mobile = $mobilearray[0];
                                    if(isset($mobilearray[1]) && sizeof($mobilearray[1])>0){
                                        $candidate_basic_info->phone = $mobilearray[1];
                                    }
                                    if ($marital_status == 'Single/unmarried') {
                                         $candidate_basic_info->marital_status = 'Single';     
                                    }
                                    else{
                                    $candidate_basic_info->marital_status = $marital_status;
                                    }
                                    $candidate_basic_info->type = $gender;
                                    $candidate_basic_info->city = $city;
                                    $candidate_basic_info->zipcode = $zipcode;
                                    $candidate_basic_info->street1 = $addressarray[0];
                                    //$candidate_basic_info->street2 = $addressarray[1];

                                    if($candidate_basic_info->save()) {
                                        $candidate_id = $candidate_basic_info->id;

                                        $experiencearray = explode(' ', $experience);
                                        $currentsalaryarray = explode(' ', $current_salary);

                                        $candidate_otherinfo = new CandidateOtherInfo();
                                        $candidate_otherinfo->candidate_id = $candidate_id;
                                        $candidate_otherinfo->current_job_title = $job_title;
                                        $candidate_otherinfo->current_employer = $current_employer;
                                        $candidate_otherinfo->experience_years = $experiencearray[0];
                                        $candidate_otherinfo->experience_months = $experiencearray[2];
                                        $candidate_otherinfo->skill = $skill;
                                        if (isset($currentsalaryarray[1]) && sizeof($currentsalaryarray)>0) {
                                            # code...
                                        $candidate_otherinfo->current_salary = $currentsalaryarray[1];
                                        }
                                        $candidate_otherinfo->owner_id = $user_id;
                                        $candidate_otherinfo->save();
                                        //CandidateOtherInfo::create($input);

                                        if ($candidate_id > 0) {
                                            $messages[] = "Record $name inserted successfully";
                                        }

                                    }
                                    else{
                                        $messages[] = "Error while inserting record $sr_no ";
                                    }
                                }

                            }
                    
                    else{
                        $messages[] = "No Data in file";
                    }

                }
            }

            return view('adminlte::candidate.import',compact('messages'));
            //return redirect()->route('client.index')->with('success','Client Created Successfully');
        }
     }
    

    public function importn2excel(){

        $messages = "Work in progress";

        return view('adminlte::candidate.import',compact('messages'));
     }

    public function importExcel(Request $request){

        $user_id = \Auth::user()->id;

        $candidateSource = CandidateBasicInfo::getCandidateimportsource();

        $candidatesource = $request->input('candidateSource');


        if($candidatesource == 'n1'){
            $candidate = CandidateController::importn1excel();
        }
        elseif ($candidatesource == 'n2') {
            $candidate = CandidateController::importn2excel();
        }

        if($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();

            $data = Excel::load($path, function ($reader) {})->get();

            $messages = array();

            if (!empty($data) && $data->count()) {

                foreach ($data->toArray() as $key => $value) {

                    if(!empty($value)) {
                        //foreach ($value as $v) {
                           //print_r($value);exit;

                            //$sr_no = $v['sr_no'];
                          //  $managed_by = $v['managed_by'];
                            $name = $value['name'];
                            $gender = $value['gender'];
                            $marital_status =$value['marital_status'];
                            $mobile_number = $value['phone_number'];
                            $email = $value['email_id'];
                            $current_employer = $value['curr_company_name'];
                            $experience = $value['total_experience'];
                            $job_title = $value['curr_company_designation'];
                            $skill = $value['key_skills'];
                            $city = $value['home_towncity'];
                            $zipcode = $value['pin_code'];
                            $address = $value['permanent_address'];
                            $current_salary = $value['annual_salary'];

                            // first check email already exist or not , if exist doesnot update data
                            $candidate_cnt = CandidateBasicInfo::checkCandidateByEmail($email);

                            if($candidate_cnt>0){
                                $messages[] = "Record $name already present ";
                            }
                            else{
                                   // $namearray = explode(' ', $name);
                                    $mobilearray = explode(',', $mobile_number);
                                    $addressarray = explode(',', $address);

                                    // Insert new candidate
                                    $candidate_basic_info = new CandidateBasicInfo();
                                    $candidate_basic_info->full_name = $name;
                                    //$candidate_basic_info->lname = $namearray[1];
                                    $candidate_basic_info->email = $email;
                                    $candidate_basic_info->mobile = $mobilearray[0];
                                    if(isset($mobilearray[1]) && sizeof($mobilearray[1])>0){
                                        $candidate_basic_info->phone = $mobilearray[1];
                                    }
                                    if ($marital_status == 'Single/unmarried') {
                                         $candidate_basic_info->marital_status = 'Single';     
                                    }
                                    else{
                                    $candidate_basic_info->marital_status = $marital_status;
                                    }
                                    $candidate_basic_info->type = $gender;
                                    $candidate_basic_info->city = $city;
                                    $candidate_basic_info->zipcode = $zipcode;
                                    $candidate_basic_info->street1 = $addressarray[0];
                                    //$candidate_basic_info->street2 = $addressarray[1];

                                    if($candidate_basic_info->save()) {
                                        $candidate_id = $candidate_basic_info->id;

                                        $experiencearray = explode(' ', $experience);
                                        $currentsalaryarray = explode(' ', $current_salary);

                                        $candidate_otherinfo = new CandidateOtherInfo();
                                        $candidate_otherinfo->candidate_id = $candidate_id;
                                        $candidate_otherinfo->current_job_title = $job_title;
                                        $candidate_otherinfo->current_employer = $current_employer;
                                        $candidate_otherinfo->experience_years = $experiencearray[0];
                                        $candidate_otherinfo->experience_months = $experiencearray[2];
                                        $candidate_otherinfo->skill = $skill;
                                        if (isset($currentsalaryarray[1]) && sizeof($currentsalaryarray)>0) {
                                            # code...
                                        $candidate_otherinfo->current_salary = $currentsalaryarray[1];
                                        }
                                        $candidate_otherinfo->owner_id = $user_id;
                                        $candidate_otherinfo->save();
                                        //CandidateOtherInfo::create($input);

                                        if ($candidate_id > 0) {
                                            $messages[] = "Record $name inserted successfully";
                                        }

                                    }
                                    else{
                                        $messages[] = "Error while inserting record $sr_no ";
                                    }
                                }

                            }
                    
                    else{
                        $messages[] = "No Data in file";
                    }

                }
            }

            return view('adminlte::candidate.import',compact('messages'));
            //return redirect()->route('client.index')->with('success','Client Created Successfully');
        }

        return view('adminlte::candidate.import',compact('messages','candidateSource'));
    }

    public function getCandidateOwner(Request $request){

        $candidate_owner = $request->get('candidate_owner');
        $id = $request->get('id');

        \DB::statement("UPDATE candidate_otherinfo SET owner_id = '$candidate_owner' where candidate_id = '$id'");

        return redirect()->route('applicant.candidate')->with('success', 'Candidate owner Updated Successfully.');
    }

    public function applicantCandidateShow($id){

        $candidateDetails = CandidateBasicInfo::getCandidateDetailsById($id);

        $candidateModel = new CandidateBasicInfo();
        $candidate_upload_type = $candidateModel->candidate_upload_type;

        $candidateDetails['files'] = array();

        $candidateFiles = CandidateUploadedResume::join('users','users.id','=','candidate_uploaded_resume.uploaded_by')
            ->select('candidate_uploaded_resume.*', 'users.name as upload_name')
            ->where('candidate_uploaded_resume.candidate_id',$id)
            ->get();

        $utils = new Utils();
        $i=0;
        if(isset($candidateFiles) && sizeof($candidateFiles) > 0)
        {
            foreach ($candidateFiles as $candidateFile)
            {
                $candidateDetails['files'][$i]['id'] = $candidateFile->id;
                $candidateDetails['files'][$i]['fileName'] = $candidateFile->file_name;
                $candidateDetails['files'][$i]['url'] = "../../".$candidateFile->file;
                $candidateDetails['files'][$i]['category'] = $candidateFile->file_type;
                $candidateDetails['files'][$i]['uploaded_by'] = $candidateFile->upload_name ;
                $candidateDetails['files'][$i]['size'] = $utils->formatSizeUnits($candidateFile->size);

                if (array_search($candidateFile->file_type, $candidate_upload_type))
                {
                    unset($candidate_upload_type[array_search($candidateFile->file_type, $candidate_upload_type)]);
                }

                $i++;
            }
        }

        $candidate_upload_type['Others'] = 'Others';
        
        $viewVariable['candidateDetails'] = $candidateDetails;    
        $viewVariable['candidate_upload_type'] = $candidate_upload_type;

        return view('adminlte::candidate.applicantcandidateshow',$viewVariable);
    }

    public function applicantCandidateEdit($id){

        $candidateSex = CandidateBasicInfo::getTypeArray();
        $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();
        $functionalRoles = FunctionalRoles::getAllFunctionalRoles();
        $educationqualification = EducationQualification::getAllEducationQualifications();

        $candidate = CandidateBasicInfo::getCandidateDetailsById($id);

        $candidate['candidate_id'] = $candidate['candidate_id'];
        $candidate['fname'] = $candidate['full_name'];
        $candidate['maritalStatus'] = $candidate['marital_status'];
        $candidate['candidateSex'] = $candidate['gender'];
        $candidate['functional_roles_id'] = $candidate['functional_roles_id'];
        $candidate['educational_qualification_id'] = $candidate['educational_qualification_id'];

        // For candidate attchments

        $candidateModel = new CandidateBasicInfo();
        $candidate_upload_type = $candidateModel->candidate_upload_type;

        $i = 0;
        $candidateDetails = array();
        $candidateFiles = CandidateUploadedResume::join('users','users.id','=','candidate_uploaded_resume.uploaded_by')
            ->select('candidate_uploaded_resume.*', 'users.name as upload_name')
            ->where('candidate_uploaded_resume.candidate_id',$id)
            ->get();

        $utils = new Utils();
        if(isset($candidateFiles) && sizeof($candidateFiles) > 0)
        {
            foreach ($candidateFiles as $candidateFile)
            {
                $candidateDetails[$i]['id'] = $candidateFile->id;
                $candidateDetails[$i]['fileName'] = $candidateFile->file_name;
                $candidateDetails[$i]['url'] = "../../".$candidateFile->file;
                $candidateDetails[$i]['category'] = $candidateFile->file_type;
                $candidateDetails[$i]['uploaded_by'] = $candidateFile->upload_name ;
                $candidateDetails[$i]['size'] = $utils->formatSizeUnits($candidateFile->size);

                if (array_search($candidateFile->file_type, $candidate_upload_type))
                {
                    unset($candidate_upload_type[array_search($candidateFile->file_type, $candidate_upload_type)]);
                }
                $i++;
            }
        }
        $candidate_upload_type['Others'] = 'Others';


        $viewVariable = array();

        $viewVariable['action'] = 'edit';
        $viewVariable['candidateSex'] = $candidateSex;
        $viewVariable['maritalStatus'] = $maritalStatus;
        $viewVariable['functionalRoles'] = $functionalRoles;
        $viewVariable['educationqualification'] = $educationqualification;
        $viewVariable['candidate'] = $candidate;
        $viewVariable['candidateDetails'] = $candidateDetails;
        $viewVariable['candidate_upload_type'] = $candidate_upload_type;

        return view('adminlte::candidate.editform',$viewVariable);
    }

    public function applicantCandidateUpdate(Request $request, $id){

        // For Applicant candidate Attchments
        $form_name = $request->form_name;
        $candidate_upload_type = $request->candidate_upload_type;
        $file = $request->file('file');

        $user_id = \Auth::user()->id;

        if(isset($file) && $file->isValid()){
            $fileName = $file->getClientOriginalName();
            $fileExtention = $file->getClientOriginalExtension();
            $fileRealPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $fileMimeType = $file->getMimeType();

            $extention = File::extension($fileName);

            $fileNameArray = explode('.',$fileName);

            $dir = 'uploads/candidate/'.$id.'/';

            if (!file_exists($dir) && !is_dir($dir)) {

                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $temp_file_name = trim($fileNameArray[0]);
            $fileNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
            $file->move($dir,$fileNewName);

            $fileNewPath = $dir.$fileNewName;

            $candidateFileUpload = new CandidateUploadedResume();
            $candidateFileUpload->candidate_id = $id;
            $candidateFileUpload->uploaded_by = $user_id;
            $candidateFileUpload->file_name = $fileNewName;
            $candidateFileUpload->file_type = $candidate_upload_type;
            $candidateFileUpload->file = $fileNewPath;
            $candidateFileUpload->mime = $fileMimeType;
            $candidateFileUpload->size = $fileSize;
            $candidateFileUpload->uploaded_date = date('Y-m-d');
            $candidateFileUpload->save();

            return redirect()->route('applicant-candidate.edit',[$id])->with('success','Attachment Uploaded Successfully.');
        }

        $candiateFname = $request->input('fname');
        $candidateEmail = $request->input('email');
        $candidateSex = $request->input('candidateSex');
        $candiateMaritalStatus = $request->input('maritalStatus');
        $candiateMobile = $request->input('mobile');
        $candiatePhone = $request->input('phone');

        $candiateStreet1 = $request->input('street1');
        $candiateStreet2 = $request->input('street2');
        $candiateCountry = $request->input('country');
        $candiateState = $request->input('state');
        $candiateCity = $request->input('city');
        $candiateZipCode = $request->input('zipcode');
        
        $candiateCurrent_employer = $request->input('current_employer');
        $candiateCurrent_job_title = $request->input('current_job_title');

        $candiateExperience_years = $request->input('experience_years');
        $candiateExperience_months = $request->input('experience_months');
        
        $candiateCurrent_salary = $request->input('current_salary');
        $candiateExpected_salary = $request->input('expected_salary');

        $candiateSkill = $request->input('skill');
        $candiateSkype_id = $request->input('skype_id');

        $functional_roles_id = $request->input('functional_roles_id');
        $specialization = $request->input('specialization');
        $educational_qualification_id = $request->input('educational_qualification_id');

        // Save Candidate Basic Info
        $candidate = CandidateBasicInfo::find($id);

        if(isset($candiateFname)){
            $candidate->full_name = $candiateFname;
        }
        if(isset($candidateEmail)){
            $candidate->email = $candidateEmail;
        }
        if(isset($candidateSex)){
            $candidate->type = $candidateSex;
        }
        if(isset($candiateMaritalStatus)){
            $candidate->marital_status = $candiateMaritalStatus;
        }
        if(isset($candiateMobile)){
            $candidate->mobile = $candiateMobile;
        }
        if(isset($candiatePhone)){
            $candidate->phone = $candiatePhone;
        }
        if(isset($candiateStreet1)){
            $candidate->street1 = $candiateStreet1;
        }
        if(isset($candiateStreet2)){
            $candidate->street2 = $candiateStreet2;
        }
        if(isset($candiateCountry)){
            $candidate->country = $candiateCountry;
        }
        if(isset($candiateState)){
            $candidate->state = $candiateState;
        }
        if(isset($candiateCity)){
            $candidate->city = $candiateCity;
        }
        if(isset($candiateZipCode)){
            $candidate->zipcode = $candiateZipCode;
        }

        $candidateStored = $candidate->save();

        if($candidateStored){

            $candidate_id = $candidate->id;

            // Save Candidate Other Info

            $candidateOtherInfo = CandidateOtherInfo::where('candidate_id',$candidate_id)->first();

            if(!isset($candidateOtherInfo) && sizeof($candidateOtherInfo) == 0){
                $candidateOtherInfo = new CandidateOtherInfo();
            }
            if(isset($candiateCurrent_job_title) && $candiateCurrent_job_title != ''){
                $candidateOtherInfo->current_job_title = $candiateCurrent_job_title;
            }
            if(isset($candiateCurrent_employer) && $candiateCurrent_employer != ''){
                $candidateOtherInfo->current_employer = $candiateCurrent_employer;
            }
            if(isset($candiateExperience_years) && $candiateExperience_years != ''){
                $candidateOtherInfo->experience_years = $candiateExperience_years;
            }
            if(isset($candiateExperience_months) && $candiateExperience_months != ''){
                $candidateOtherInfo->experience_months = $candiateExperience_months;
            }
            if(isset($candiateCurrent_salary) && $candiateCurrent_salary != ''){
                $candidateOtherInfo->current_salary = $candiateCurrent_salary;
            }
            if(isset($candiateExpected_salary) && $candiateExpected_salary != ''){
                $candidateOtherInfo->expected_salary = $candiateExpected_salary;
            }
            if(isset($candiateSkill) && $candiateSkill != ''){
                $candidateOtherInfo->skill = $candiateSkill;
            }
            if(isset($candiateSkype_id) && $candiateSkype_id != ''){
                $candidateOtherInfo->skype_id = $candiateSkype_id;
            }
            if(isset($functional_roles_id) && $functional_roles_id != ''){
                $candidateOtherInfo->functional_roles_id = $functional_roles_id;
            }
            if(isset($specialization) && $specialization != ''){
                $candidateOtherInfo->specialization = $specialization;
            }
            if(isset($educational_qualification_id) && $educational_qualification_id != ''){
                $candidateOtherInfo->educational_qualification_id = $educational_qualification_id;
            }

            $candidateOtherInfo->login_candidate = '1';
            $candidateOtherInfo->save();
        }

        return redirect()->route('applicant.candidate')->with('success','Candidate Details Updated Successfully.');
    }
}