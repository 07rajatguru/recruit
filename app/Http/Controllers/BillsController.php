<?php

namespace App\Http\Controllers;

use App\Bills;
use App\BillsDoc;
use App\BillsEffort;
use App\CandidateBasicInfo;
use App\ClientBasicinfo;
use App\JobOpen;
use App\JobCandidateJoiningdate;
use Illuminate\Http\Request;
use App\Date;
use App\User;
use Illuminate\Support\Facades\Input;
use Excel;
use App\Utils;
use App\Events\NotificationMail;

class BillsController extends Controller
{
    public function index()
    {
        $cancel_bill = 0;

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $bnm = Bills::getAllBills(0,1,$user_id);
            $access = true;
        }
        else{
            $bnm = Bills::getAllBills(0,0,$user_id);
            $access = false;
        }

        $count = sizeof($bnm);

        $title = "Forecasting";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title','isSuperAdmin','isAccountant','count','cancel_bill'));
    }

    public function cancelbnm(){

        $cancel_bill = 1;
        $cancel_bnm = 1;
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $bnm = Bills::getCancelBills(0,1,$user_id);
            $access = true;
        }
        else{
            $bnm = Bills::getCancelBills(0,0,$user_id);
            $access = false;
        }

        $count = sizeof($bnm);

        $title = "Forecasting";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title','isSuperAdmin','isAccountant','count','cancel_bill','cancel_bnm'));
    }

    public function billsMade(){
       // $bnm = Bills::getAllBills(1);
        $cancel_bill = 0;
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $bnm = Bills::getAllBills(1,1,$user_id);
            $access = true;
        }
        else{
            $bnm = Bills::getAllBills(1,0,$user_id);
            $access = false;
        }

        $count = sizeof($bnm);
        $title = "Recovery";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title','isSuperAdmin','isAccountant','count','cancel_bill'));

    }

    public function cancelbm(){
       // $bnm = Bills::getAllBills(1);
        $cancel_bill = 1;
        $cancel_bnm = 0;
        $cancel_bn = 1;
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $accountant_role_id = env('ACCOUNTANT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$accountant_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $bnm = Bills::getCancelBills(1,1,$user_id);
            $access = true;
        }
        else{
            $bnm = Bills::getCancelBills(1,0,$user_id);
            $access = false;
        }

        $count = sizeof($bnm);
        $title = "Recovery";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title','isSuperAdmin','isAccountant','count','cancel_bill','cancel_bnm','cancel_bn'));

    }

    public function create()
    {
        $action = 'add';
        $generate_bm = '0';
        $status = '0';

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllBillsJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllBillsJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['posting_title']." - ".$v['company_name']." ,".$v['location'];
        }
        $job_id = 0;

        $users = User::getAllUsersCopy('recruiter');

        $employee_name = array();
        $employee_percentage = array();

        $employee_name[0] = $user_id;
        $employee_percentage[0] = '0';
        for ($i = 1; $i < 5; $i++) {
            $employee_name[$i] = '';
            $employee_percentage[$i] = '';
        }

        $candidate_id = '';
        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();
        return view('adminlte::bills.create', compact('action','generate_bm','jobopen','job_id','users','employee_name','employee_percentage','candidate_id','candidateSource','status','isSuperAdmin','isAccountant'));
    }

    public function store(Request $request)
    {

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        $upload_documents = $request->file('upload_documents');

        $input = $request->all();

        $job_id = $input['jobopen'];
        $candidate_id = $input['candidate_name'];
        $company_name = $input['company_name'];
        $candidate_contact_number = $input['candidate_contact_number'];
        $date_of_joining = $input['date_of_joining'];
        $fixed_salary = $input['fixed_salary'];
        $source = $input['source'];
        $client_contact_number = $input['client_contact_number'];
        $candidate_name = $input['candidate_name'];
        $designation_offered = $input['designation_offered'];
        $job_location = $input['job_location'];
        //$percentage_charged = $input['percentage_charged'];
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];

        if(isset($input['percentage_charged']) && $input['percentage_charged']!='')
            $percentage_charged = $input['percentage_charged'];
        else
            $percentage_charged = '';

        $employee_name = array();
        $employee_final = array();
        $employee_percentage = array();

        $employee_name[] = $input['employee_name_1'];
        $employee_name[] = $input['employee_name_2'];
        $employee_name[] = $input['employee_name_3'];
        $employee_name[] = $input['employee_name_4'];
        $employee_name[] = $input['employee_name_5'];

        $employee_percentage[] = $input['employee_percentage_1'];
        $employee_percentage[] = $input['employee_percentage_2'];
        $employee_percentage[] = $input['employee_percentage_3'];
        $employee_percentage[] = $input['employee_percentage_4'];
        $employee_percentage[] = $input['employee_percentage_5'];
        $total = 0;
        foreach ($employee_name as $k => $v) {
            if ($v != '' && $v!=0) {
                $employee_final[$v] = $employee_percentage[$k];
                $total += $employee_percentage[$k];
            }
        }

       if($total>100){
           return redirect('forecasting/create')->withInput(Input::all())->with('error','Total percentage of efforts should be less than or equal to 100');
       }

        //echo $dateClass->changeDMYtoYMD($date_of_joining);exit;
        $bill = new Bills();

        $bill->receipt_no = 'xyz';
        $bill->company_name = $company_name;
        $bill->candidate_contact_number = $candidate_contact_number;
        $bill->date_of_joining = $dateClass->changeDMYtoYMD($date_of_joining);
        $bill->fixed_salary = $fixed_salary;
        $bill->source = $source;
        $bill->client_contact_number = $client_contact_number;
        $bill->candidate_name = $candidate_name;
        $bill->designation_offered = $designation_offered;
        $bill->job_location = $job_location;
        if(isset($percentage_charged) && $percentage_charged!='')
            $bill->percentage_charged = $percentage_charged;
        else
            $bill->percentage_charged = 0;

        $bill->client_name = $client_name;
        $bill->client_email_id = $client_email_id;
        $bill->address_of_communication = $address_of_communication;
        $bill->status = 0; // 0- BNM
        $bill->remarks = '';
        $bill->uploaded_by = $user_id;
        $bill->job_id = $job_id;
        $bill->candidate_id = $candidate_id;

        $validator = \Validator::make(Input::all(),$bill::$rules);

        if($validator->fails()){
            return redirect('forecasting/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $bill_response = $bill->save();

        if ($bill_response) {
            $bill_id = $bill->id;

            foreach ($employee_final as $k => $v) {
                $bill_efforts = new BillsEffort();

                $bill_efforts->bill_id = $bill_id;
                $bill_efforts->employee_name = $k;
                $bill_efforts->employee_percentage = $v;

                $bill_efforts->save();
            }

            if (isset($upload_documents) && sizeof($upload_documents) > 0) {
                foreach ($upload_documents as $k => $v) {
                    if (isset($v) && $v->isValid()) {
                        // echo "here";
                        $file_name = $v->getClientOriginalName();
                        $file_extension = $v->getClientOriginalExtension();
                        $file_realpath = $v->getRealPath();
                        $file_size = $v->getSize();

                        //$extention = File::extension($file_name);

                        $dir = 'uploads/bills/' . $bill_id . '/';

                        if (!file_exists($dir) && !is_dir($dir)) {
                            mkdir($dir, 0777, true);
                            chmod($dir, 0777);
                        }
                        $v->move($dir, $file_name);

                        $file_path = $dir . $file_name;

                        $bills_doc = new BillsDoc();
                        $bills_doc->bill_id = $bill_id;
                        $bills_doc->file = $file_path;
                        $bills_doc->name = $file_name;
                        $bills_doc->size = $file_size;
                        $bills_doc->created_at = date('Y-m-d');
                        $bills_doc->updated_at = date('Y-m-d');

                        $bills_doc->save();
                    }

                }
            }

        }

        JobCandidateJoiningdate::where('job_id','=',$job_id)->where('candidate_id','=',$candidate_id)->delete();

        $candidatejoindate = new JobCandidateJoiningdate();
        $candidatejoindate->job_id = $job_id;
        $candidatejoindate->candidate_id = $candidate_id;
        $candidatejoindate->joining_date = $dateClass->changeDMYtoYMD($date_of_joining);
        $candidatejoindate->fixed_salary = $fixed_salary;
        $candidatejoindate->save();

        // For forcasting mail [email_notification table entry every minute check]
        $user_email = \Auth::user()->email;
        $superadminuserid = getenv('SUPERADMINUSERID');
        $accountantuserid = getenv('ACCOUNTANTUSERID');

        $superadminemail = User::getUserEmailById($superadminuserid);
        $accountantemail = User::getUserEmailById($accountantuserid);

        $cc_users_array = array($superadminemail,$accountantemail);

        $c_name = CandidateBasicInfo::getCandidateNameById($candidate_name);

        $module = "Forecasting";
        $sender_name = $user_id;
        $to = $user_email;

        $cc_users_array = array_filter($cc_users_array);
        $cc = implode(",",$cc_users_array);
        
        $subject = "Forecasting - " . $c_name;
        $message = "Forecasting - " . $c_name;
        $module_id = $bill_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('forecasting.index')->with('success', 'Bills Created Successfully');
    }

    public function show($id){

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

       $viewVariable = Bills::getShowBill($id);

       return view('adminlte::bills.show', $viewVariable,compact('isSuperAdmin','isAccountant'));
    }

    public function edit($id)
    {
        $action = 'edit';
        $generate_bm ='0';
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllBillsJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllBillsJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['posting_title']." - ".$v['company_name'];
        }
        
        $bnm = Bills::find($id);
        //print_r($bnm);exit;

        $dateClass = new Date();
        $doj = $dateClass->changeYMDtoDMY($bnm->date_of_joining);
        $job_id = $bnm->
        $action = 'edit';
        $status = $bnm->status;

        if($status == 1){
            $generate_bm = '1';
        }


        $employee_name = array();
        $employee_percentage = array();
        for ($i = 0; $i < 5; $i++) {
            $employee_name[$i] = '';
            $employee_percentage[$i] = '';
        }

        $efforts = Bills::getEmployeeEffortsById($id);

        // set employee name and percentage
        $i = 0;
        if (isset($efforts) && sizeof($efforts) > 0) {
            foreach ($efforts as $k => $v) {
                $employee_name[$i] = $k;
                $employee_percentage[$i] = $v;
                $i++;
            }
        }

        $job_id = $bnm->job_id;
        $candidate_id = $bnm->candidate_id;
        $users = User::getAllUsersCopyWithInactive('recruiter');
        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();

            $i = 0;
            
            $billsdetails['files'] = array();
            $billsFiles = BillsDoc::select('bills_doc.*')
                ->where('bills_doc.bill_id',$id)
                ->get();
            $utils = new Utils();
            if(isset($billsFiles) && sizeof($billsFiles) > 0){
                foreach ($billsFiles as $billfile) {
                    $billsdetails['files'][$i]['id'] = $billfile->id;
                    $billsdetails['files'][$i]['fileName'] = $billfile->file;
                    $billsdetails['files'][$i]['url'] = "../../".$billfile->file;
                    $billsdetails['files'][$i]['name'] = $billfile->name ;
                    $billsdetails['files'][$i]['size'] = $utils->formatSizeUnits($billfile->size);

                    $i++;

                }
            }

        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm','doj','jobopen','job_id','users','candidate_id','candidateSource','billsdetails','id','status','isSuperAdmin','isAccountant'));

    }

    public function update(Request $request, $id)
    {
        $dateClass = new Date();
        $user_id = \Auth::user()->id;

        $input = $request->all();

        $job_id = $input['jobopen'];
        $company_name = $input['company_name'];
        $candidate_contact_number = $input['candidate_contact_number'];
        $date_of_joining = $input['date_of_joining'];
        $fixed_salary = $input['fixed_salary'];
        $source = $input['source'];
        $client_contact_number = $input['client_contact_number'];
        $candidate_id = $input['candidate_name'];
        $designation_offered = $input['designation_offered'];
        $job_location = $input['job_location'];
        //$percentage_charged = $input['percentage_charged'];
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];
        $generateBM = $input['generateBM'];
        $status=0;
        if($generateBM==1){
            $status = 1;
        }

        if(isset($input['percentage_charged']) && $input['percentage_charged']!='')
            $percentage_charged = $input['percentage_charged'];
        else
            $percentage_charged = '';

        $employee_name = array();
        $employee_final = array();
        $employee_percentage = array();

        $employee_name[] = $input['employee_name_1'];
        $employee_name[] = $input['employee_name_2'];
        $employee_name[] = $input['employee_name_3'];
        $employee_name[] = $input['employee_name_4'];
        $employee_name[] = $input['employee_name_5'];

        $employee_percentage[] = $input['employee_percentage_1'];
        $employee_percentage[] = $input['employee_percentage_2'];
        $employee_percentage[] = $input['employee_percentage_3'];
        $employee_percentage[] = $input['employee_percentage_4'];
        $employee_percentage[] = $input['employee_percentage_5'];
        $total = 0;
        foreach ($employee_name as $k => $v) {
            if ($v != '') {
                $employee_final[$v] = $employee_percentage[$k];
                $total += (int)$employee_percentage[$k];
            }
        }

        if($total>100){
            return redirect('forecasting/'.$id.'/edit')->withInput(Input::all())->with('error','Total percentage of efforts should be less than or equal to 100');
        }

        $bill = Bills::find($id);

        $uploaded_by = $bill->uploaded_by;
        //print_r($uploaded_by);exit;

        $bill->receipt_no = 'xyz';
        $bill->company_name = $company_name;
        $bill->candidate_contact_number = $candidate_contact_number;
        $bill->date_of_joining = $dateClass->changeDMYtoYMD($date_of_joining);
        $bill->fixed_salary = $fixed_salary;
        $bill->source = $source;
        $bill->client_contact_number = $client_contact_number;
        $bill->candidate_name = $candidate_id;
        $bill->designation_offered = $designation_offered;
        $bill->job_location = $job_location;
        //$bill->percentage_charged = $percentage_charged;
        $bill->client_name = $client_name;
        $bill->client_email_id = $client_email_id;
        $bill->address_of_communication = $address_of_communication;
        $bill->status = $status; // 0- BNM , 1- BM
        $bill->remarks = '';
        $bill->uploaded_by = $uploaded_by;
        $bill->job_id = $job_id;
        $bill->candidate_id = $candidate_id;

        if(isset($percentage_charged) && $percentage_charged!='')
            $bill->percentage_charged = $percentage_charged;
        else
            $bill->percentage_charged = 0;

        $validator = \Validator::make(Input::all(),$bill::$rules);

        if($validator->fails()){
            return redirect('forecasting/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }
        else{

            $bill_response = $bill->save();
            BillsEffort::where('bill_id','=',$id)->delete();
            foreach ($employee_final as $k => $v) {

                if($k>0){
                    $bill_efforts = new BillsEffort();

                    $bill_efforts->bill_id = $id;
                    $bill_efforts->employee_name = $k;
                    $bill_efforts->employee_percentage = $v;

                    $bill_efforts->save();
                }

            }
        }
         $file = $request->file('file');
        if (isset($file) && $file->isValid()) {
            $file_name = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();
            $file_realpath = $file->getRealPath();
            $file_size = $file->getSize();
            $dir = 'uploads/bills/' . $id . '/';

            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $file->move($dir, $file_name);
            $file_path = $dir . $file_name;

            $bills_doc = new BillsDoc();
            $bills_doc->bill_id = $id;
            $bills_doc->file = $file_path;
            $bills_doc->name = $file_name;
            $bills_doc->size = $file_size;
            $bills_doc->created_at = date('Y-m-d');
            $bills_doc->updated_at = date('Y-m-d');

            $bills_doc->save();

            if ($status == 1) {
                return redirect('recovery/'.$id.'/generaterecovery');
            }
            else{
             return redirect('forecasting/'.$id.'/edit');
            }
        }

        JobCandidateJoiningdate::where('job_id','=',$job_id)->where('candidate_id','=',$candidate_id)->delete();

        $candidatejoindate = new JobCandidateJoiningdate();
        $candidatejoindate->job_id = $job_id;
        $candidatejoindate->candidate_id = $candidate_id;
        $candidatejoindate->joining_date = $dateClass->changeDMYtoYMD($date_of_joining);
        $candidatejoindate->fixed_salary = $fixed_salary;
        $candidatejoindate->save();

        if ($status == 1) {
            // For Recovery mail [email_notification table entry every minute check]
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($candidate_id);

            $module = "Recovery";
            $sender_name = $user_id;
            $to = $user_email;

            $cc_users_array = array_filter($cc_users_array);
            $cc = implode(",",$cc_users_array);
            
            $subject = "Recovery - ". $c_name;
            $message = "Recovery - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($status == 1){
            return redirect()->route('bills.recovery')->with('success', 'Recovery Updated Successfully');
        }
        else{
            return redirect()->route('forecasting.index')->with('success', 'Forecasting Updated Successfully');
        }
    }

    public function delete($id){

        BillsEffort::where('bill_id',$id)->delete();
        BillsDoc::where('bill_id',$id)->delete();
        $todo = Bills::where('id',$id)->delete();

        return redirect()->route('forecasting.index')->with('success','Bill Deleted Successfully');

    }

    public function cancel($id){
        
        $cancel_bill =1;
        $bills = array();
        $bill = Bills::find($id);
        $bills['status'] = $bill->status;
        $bills['job_id'] = $bill->job_id;
        $bills['candidate_id'] = $bill->candidate_id;
        $bill->cancel_bill = $cancel_bill;
        $bill_cancel = $bill->save();

        //print_r($bill_cancel);exit;
        $candidate_join_delete = JobCandidateJoiningdate::where('job_id',$bills['job_id'])->where('candidate_id',$bills['candidate_id'])->delete();

        if ($bills['status'] == 1) {
            // For Cancel Recovery mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Cancel Recovery";
            $sender_name = $user_id;
            $to = $user_email;

            $cc_users_array = array_filter($cc_users_array);
            $cc = implode(",",$cc_users_array);
            
            $subject = "Cancel Recovery - ". $c_name;
            $message = "Cancel Recovery - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
        else if ($bills['status'] == 0) {
            // For Cancel Forecasting mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Cancel Forecasting";
            $sender_name = $user_id;
            $to = $user_email;

            $cc_users_array = array_filter($cc_users_array);
            $cc = implode(",",$cc_users_array);
            
            $subject = "Cancel Forecasting - ". $c_name;
            $message = "Cancel Forecasting - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($bills['status'] == 1){
            return redirect()->route('bills.recovery')->with('success', 'Recovery Canceled Successfully');
        }
        else{
            return redirect()->route('forecasting.index')->with('success', 'Forecasting Canceled Successfully');
        }

    }

    public function reliveBill($id){

        $relive_bill = 0;
        $bills = array();
        $bill = Bills::find($id);
        $bills['status'] = $bill->status;
        $bills['job_id'] = $bill->job_id;
        $bills['candidate_id'] = $bill->candidate_id;
        $bills['joining_date'] = $bill->date_of_joining;
        $bills['fixed_salary'] = $bill->fixed_salary;
        $bill->cancel_bill = $relive_bill;
        $bill_cancel = $bill->save();
        //print_r($bills);exit;

        $candidatejoindate = new JobCandidateJoiningdate();
        $candidatejoindate->job_id = $bills['job_id'];
        $candidatejoindate->candidate_id = $bills['candidate_id'];
        $candidatejoindate->joining_date = $bills['joining_date'];
        $candidatejoindate->fixed_salary = $bills['fixed_salary'];
        $candidatejoindate->save();

        if ($bills['status'] == 1) {
            // For Relive Recovery mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Relive Recovery";
            $sender_name = $user_id;

            $cc_users_array = array_filter($cc_users_array);
            $to = implode(",",$cc_users_array);
            $cc = $superadminemail;
            
            $subject = "Relive Recovery - ". $c_name;
            $message = "Relive Recovery - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }
        else if ($bills['status'] == 0) {
            // For Relive Forecasting mail [email_notification table entry]
            $user_id = \Auth::user()->id;
            $user_email = \Auth::user()->email;
            $superadminuserid = getenv('SUPERADMINUSERID');
            $accountantuserid = getenv('ACCOUNTANTUSERID');

            $superadminemail = User::getUserEmailById($superadminuserid);
            $accountantemail = User::getUserEmailById($accountantuserid);

            $cc_users_array = array($superadminemail,$accountantemail);

            $c_name = CandidateBasicInfo::getCandidateNameById($bills['candidate_id']);

            $module = "Relive Forecasting";
            $sender_name = $user_id;

            $cc_users_array = array_filter($cc_users_array);
            $to = implode(",",$cc_users_array);
            $cc = $superadminemail;
            
            $subject = "Relive Forecasting - ". $c_name;
            $message = "Relive Forecasting - ". $c_name;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($bills['status'] == 1){
            return redirect()->route('bills.recovery')->with('success', 'Recovery Relived Successfully');
        }
        else{
            return redirect()->route('forecasting.index')->with('success', 'Forecasting Relived Successfully');
        }
    }

    public function attachmentsDestroy($id){

        $billFileDetails = BillsDoc::find($id);

        $billId =  $billFileDetails->bill_id;

        unlink($billFileDetails->file);

        $billFileDelete = BillsDoc::where('id',$id)->delete();

        //$billId = $_POST['id'];

        return redirect()->route('forecasting.show',[$billId])->with('success','Attachment deleted Successfully');
    }

    public function upload(Request $request){

        $user_id = \Auth::user()->id;
        $upload_documents = $request->upload_documents;
        $file = $request->file('file');
        $bill_id = $request->id;
       // print_r($bill_id);exit;

        if (isset($file) && $file->isValid()) {
            $file_name = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();
            $file_realpath = $file->getRealPath();
            $file_size = $file->getSize();
            $dir = 'uploads/bills/' . $bill_id . '/';

            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $file->move($dir, $file_name);
            $file_path = $dir . $file_name;

            $bills_doc = new BillsDoc();
            $bills_doc->bill_id = $bill_id;
            $bills_doc->file = $file_path;
            $bills_doc->name = $file_name;
            $bills_doc->size = $file_size;
            $bills_doc->created_at = date('Y-m-d');
            $bills_doc->updated_at = date('Y-m-d');

            $bills_doc->save();
        }
        return redirect()->route('forecasting.show',[$bill_id])->with('success','Attachment uploaded successfully');
    }

    public function generateBM($id){

        $generate_bm = '1';

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllBillsJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllBillsJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['posting_title']." - ".$v['company_name'];
        }

        $bnm = Bills::find($id);
        $status = $bnm->status;

        $action = 'edit';

        $employee_name = array();
        $employee_percentage = array();
        for ($i = 0; $i < 5; $i++) {
            $employee_name[$i] = '';
            $employee_percentage[$i] = '';
        }

        $efforts = Bills::getEmployeeEffortsById($id);

        // set employee name and percentage
        $i = 0;
        if (isset($efforts) && sizeof($efforts) > 0) {
            foreach ($efforts as $k => $v) {
                $employee_name[$i] = $k;
                $employee_percentage[$i] = $v;
                $i++;
            }
        }

        $job_id = $bnm->job_id;
        $candidate_id = $bnm->candidate_id;
        $users = User::getAllUsersCopyWithInactive('recruiter');
        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();

         $i = 0;
            
            $billsdetails['files'] = array();
            $billsFiles = BillsDoc::select('bills_doc.*')
                ->where('bills_doc.bill_id',$id)
                ->get();
            $utils = new Utils();
            if(isset($billsFiles) && sizeof($billsFiles) > 0){
                foreach ($billsFiles as $billfile) {
                    $billsdetails['files'][$i]['id'] = $billfile->id;
                    $billsdetails['files'][$i]['fileName'] = $billfile->file;
                    $billsdetails['files'][$i]['url'] = "../../".$billfile->file;
                    $billsdetails['files'][$i]['name'] = $billfile->name ;
                    $billsdetails['files'][$i]['size'] = $utils->formatSizeUnits($billfile->size);

                    $i++;

                }
            }

        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm','jobopen','job_id','candidate_id','users','candidateSource','billsdetails','status','isSuperAdmin','isAccountant'));

    }

    public function downloadExcel(){

        $ids = $_POST['ids'];

        $response = Bills::getBillsByIds($ids);

        ob_end_clean();

        ob_start();

        Excel::create('Laravel Excel', function($excel) use ($response){

            $excel->sheet('Excel sheet', function($sheet) use ($response) {

                $sheet->setOrientation('landscape');

            });

        })->export('xlsx');
        ob_flush();
        exit();

    }

    public function getClientInfo(){

        $job_id = $_GET['job_id'];

        // get client info
        $client = ClientBasicinfo::getClientInfoByJobId($job_id);

        echo json_encode($client);exit;

    }

    public function getCandidateInfo(){

        $job_id = $_GET['job_id'];

        // get candidate Info
        $response = array();
        $response['returnvalue'] = 'invalid';

        $candidate_data = CandidateBasicInfo::getCandidateInfoByJobId($job_id);

        if(isset($candidate_data) && sizeof($candidate_data)>0) {
            $response['returnvalue'] = 'valid';
            $response['data'] = $candidate_data;
        }

        echo json_encode($response);exit;

    }

    public function SendConfirmationMail($id){
        
        $user_id = \Auth::user()->id;
        
        //Logged in User Email Id
        $user_email = User::getUserEmailById($user_id);

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');

        $join_mail = Bills::getJoinConfirmationMail($id);
        //$client_email_id = $join_mail['client_email_id'];
        $candidate_name = $join_mail['candidate_name'];
        $candidate_id = $join_mail['candidate_id'];
        //print_r($join_mail);exit;

        $candidate_email = Bills::getCandidateOwnerEmail($id);
        $candidate_owner_email = $candidate_email->candidateowneremail;

        $client_email = Bills::getClientOwnerEmail($id);
        $client_owner_email = $client_email->clientowneremail;

        $to_address = $user_email;

        $cc_address = array();
        $cc_address[] = $client_owner_email;
        $cc_address[] = $candidate_owner_email;

        $input = array();
        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $to_address;
        $input['cc'] = $cc_address;
        $input['join_mail'] = $join_mail;
        $input['candidate_name'] = $candidate_name;

        \Mail::send('adminlte::emails.joinconfirmationmail', $input, function ($message) use ($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->cc($input['cc'])->subject('Joining Confirmation of '. $input['candidate_name']);
        });

        return redirect('/recovery');
    }

}