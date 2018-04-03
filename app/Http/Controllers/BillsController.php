<?php

namespace App\Http\Controllers;

use App\Bills;
use App\BillsDoc;
use App\BillsEffort;
use App\CandidateBasicInfo;
use App\ClientBasicinfo;
use App\JobOpen;
use Illuminate\Http\Request;
use App\Date;
use App\User;
use Illuminate\Support\Facades\Input;
use Excel;

class BillsController extends Controller
{
    public function index()
    {

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $bnm = Bills::getAllBills(0,1,$user_id);
            $access = true;
        }
        else{
            $bnm = Bills::getAllBills(0,0,$user_id);
            $access = false;
        }

        $title = "Bills Not Made";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title'));
    }

    public function billsMade(){
       // $bnm = Bills::getAllBills(1);
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $bnm = Bills::getAllBills(1,1,$user_id);
            $access = true;
        }
        else{
            $bnm = Bills::getAllBills(1,0,$user_id);
            $access = false;
        }
        $title = "Bills Made";
        return view('adminlte::bills.index', compact('bnm','access','user_id','title'));
    }

    public function create()
    {
        $action = 'add';
        $generate_bm = '0';

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

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
        return view('adminlte::bills.create', compact('action','generate_bm','jobopen','job_id','users','employee_name','employee_percentage','candidate_id','candidateSource'));
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
        $percentage_charged = $input['percentage_charged'];
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];

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
           return redirect('bnm/create')->withInput(Input::all())->with('error','Total percentage of efforts should be less than or equal to 100');
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
            return redirect('bnm/create')->withInput(Input::all())->withErrors($validator->errors());
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

        return redirect()->route('bnm.index')->with('success', 'Bills Created Successfully');
    }

    public function edit($id)
    {
        $action = 'edit';
        $generate_bm ='0';
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

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

        $dateClass = new Date();
        $doj = $dateClass->changeYMDtoDMY($bnm->date_of_joining);
        $job_id = $bnm->
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
        $users = User::getAllUsersCopy('recruiter');
        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();
        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm','doj','jobopen','job_id','users','candidate_id','candidateSource'));

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
        $percentage_charged = $input['percentage_charged'];
        $client_name = $input['client_name'];
        $client_email_id = $input['client_email_id'];
        $address_of_communication = $input['address_of_communication'];
        $generateBM = $input['generateBM'];
        $status=0;
        if($generateBM==1){
            $status = 1;
        }

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
            return redirect('bnm/'.$id.'/edit')->withInput(Input::all())->with('error','Total percentage of efforts should be less than or equal to 100');
        }

        $bill = Bills::find($id);

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
        $bill->percentage_charged = $percentage_charged;
        $bill->client_name = $client_name;
        $bill->client_email_id = $client_email_id;
        $bill->address_of_communication = $address_of_communication;
        $bill->status = $status; // 0- BNM , 1- BM
        $bill->remarks = '';
        $bill->uploaded_by = $user_id;
        $bill->job_id = $job_id;
        $bill->candidate_id = $candidate_id;

        $validator = \Validator::make(Input::all(),$bill::$rules);

        if($validator->fails()){
            return redirect('bnm/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }
        else{

            $bill_response = $bill->save();
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

        return redirect()->route('bnm.index')->with('success', 'BNM Updated Successfully');
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
        $users = User::getAllUsersCopy('recruiter');
        $candidateSource = CandidateBasicInfo::getCandidateSourceArrayByName();
        return view('adminlte::bills.edit', compact('bnm', 'action', 'employee_name', 'employee_percentage','generate_bm','jobopen','job_id','candidate_id','users','candidateSource'));

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

}