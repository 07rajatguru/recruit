<?php

namespace App\Http\Controllers;

use App\Companies;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Input;
use App\Role;
use DB;
use Hash;
use App\Date;
use App\UserOthersInfo;
use App\UsersDoc;
use App\Utils;
use App\UserLeave;
use App\Events\NotificationMail;
use App\UsersLog;
use App\UsersFamily;
use App\Training;
use App\TrainingVisibleUser;
use App\ProcessManual;
use App\ProcessVisibleUser;
use App\JobOpen;
use App\JobVisibleUsers;
use App\RoleUser;
use App\ModuleVisibleUser;
use App\CandidateBasicInfo;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {   
        $user =  \Auth::user();
        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isOfficeAdmin = $user_obj::isOfficeAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $data = User::orderBy('status','ASC')->get();
        return view('adminlte::users.index',compact('data','isSuperAdmin','isAccountant','isOfficeAdmin'))
            ->with('i', ($request->input('page', 1) - 1) * 5);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $user_id = \Auth::user()->id;

        $roles = Role::orderBy('display_name','ASC')->pluck('display_name','id')->toArray();
        $reports_to = User::getUserArray($user_id);
        $reports_to = array_fill_keys(array(0),'Select Reports to')+$reports_to;

        //$floor_incharge = User::getAllUsers();
        $floor_incharge = User::getAllFloorInchargeUsers();
        $floor_incharge = array_fill_keys(array(0),'Select Floor Incharge')+$floor_incharge;

        $companies = Companies::getCompanies();
        $companies = array_fill_keys(array(''),'Select Company')+$companies;

        $type  = User::getTypeArray();
        $type = array_fill_keys(array(''),'Select type')+$type;

        return view('adminlte::users.create',compact('roles', 'reports_to','companies','type','floor_incharge'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
           // 'company_id' => 'required'
            'type' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }
        $reports_to = $request->input('reports_to');
        $floor_incharge = $request->input('floor_incharge');
        $type = $request->input('type');
        $check_report = $request->input('daily_report');
        $status = $request->input('status');
        $account_manager = $request->input('account_manager');
        $role_id = $request->input('roles');
        //print_r($account_manager);exit;

        // Start Report Status

        $cv_report = $request->input('cv_report');

        if(isset($cv_report) && $cv_report != '') {
            $user->cv_report = $cv_report;
        }
        else {
            $user->cv_report = NULL;
        }

        $interview_report = $request->input('interview_report');

        if(isset($interview_report) && $interview_report != '') {
            $user->interview_report = $interview_report;
        }
        else {
            $user->interview_report = NULL;
        }

        $lead_report = $request->input('lead_report');

        if(isset($lead_report) && $lead_report != '') {
            $user->lead_report = $lead_report;
        }
        else {
            $user->lead_report = NULL;
        }

        // End Report Status

        // Get first & last name

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');

        // Floor incharge or not

        $check_floor_incharge = $request->input('check_floor_incharge');

        $user->secondary_email=$request->input('semail');
        $user->daily_report = $check_report;
        $user->reports_to = $reports_to;
        $user->floor_incharge = $floor_incharge;
        $user->status = $status;
        $user->account_manager = $account_manager;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->check_floor_incharge = $check_floor_incharge;

        $users = $user->save();

        $user_id = $user->id;

        // Add new User to training & process manual if it is for all users and entry in database
        if ($type == 'recruiter' && $status == 'Active') {
            $training_id = Training::getAlltrainingIds(1);
            if (isset($training_id) && $training_id != '') {
                foreach ($training_id as $key => $value) {
                    $training_visible_users = new TrainingVisibleUser;
                    $training_visible_users->training_id = $value;
                    $training_visible_users->user_id = $user_id;
                    $training_visible_users->save();
                }
            }
            //print_r($training_id);exit;
        }
        if ($status == 'Active') {
            $process_id = ProcessManual::getAllprocessmanualIds(1);
            if (isset($process_id) && $process_id != '') {
                foreach ($process_id as $key => $value) {
                    $process_visible_users = new ProcessVisibleUser();
                    $process_visible_users->process_id = $value;
                    $process_visible_users->user_id = $user_id;
                    $process_visible_users->save();
                }
            }
        }

        // If job_open_to_all = 1 then new user visible that all jobs
        if ($type == 'recruiter' && $status == 'Active') {
            $job_id = JobOpen::getAllJobsId(1);
            if (isset($job_id) && $job_id != '') {
                foreach ($job_id as $key => $value){
                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $value;
                    $job_visible_users->user_id = $user_id;
                    $job_visible_users->save();
                }
            }
        }

         // Add new user module visibility by it's role id
        if (isset($role_id) && $role_id > 0) {
            $other_user_id = RoleUser::getUserIdByRoleId($role_id);
            if (isset($other_user_id) && $other_user_id > 0) {
                $module_arr = ModuleVisibleUser::getModuleByUserId($other_user_id);
                $module_id = array();
                $i = 0;
                if (isset($module_arr) && sizeof($module_arr)>0) {
                    foreach ($module_arr as $key => $value) {
                        $module_id[$i] = $key;
                        $i++;
                    }
                    if (isset($module_id) && sizeof($module_id)>0) {
                        foreach ($module_id as $key => $value) {
                            $module_user_add = new ModuleVisibleUser();
                            $module_user_add->user_id = $user_id;
                            $module_user_add->module_id = $value;
                            $module_user_add->save();
                        }
                    }
                }
            }
        }
     
        return redirect()->route('users.index')->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $user = User::find($id);
        return view('adminlte::users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::orderBy('display_name','ASC')->pluck('display_name','id');

        $reports_to = User::getUserArray($id);
        $reports_to = array_fill_keys(array(0),'Select Reports to')+$reports_to;

        //$floor_incharge = User::getAllUsers();
        $floor_incharge = User::getAllFloorInchargeUsers();
        $floor_incharge = array_fill_keys(array(0),'Select Floor Incharge')+$floor_incharge;

        $userRole = $user->roles->pluck('id','id')->toArray();
        $userReportsTo = $user->reports_to;
        $userFloorIncharge = $user->floor_incharge;
        $semail=$user->secondary_email;

        $companies = Companies::getCompanies();
        $companies = array_fill_keys(array(''),'Select Company')+$companies;

        $type  = User::getTypeArray();
        $type = array_fill_keys(array(''),'Select type')+$type;
      
        return view('adminlte::users.edit',compact('user','roles','userRole', 'reports_to', 'userReportsTo','userFloorIncharge','companies','type','floor_incharge','semail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'same:confirm-password',
            'roles' => 'required',
         //   'company_id' => 'required'
            'type' => 'required'

        ]);


        $input = $request->all();

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }

        if(!empty($input['reports_to'])){
            $input['reports_to'] = $input['reports_to'];
        }else{
            $input = array_except($input,array('reports_to'));
        }

        if(!empty($input['floor_incharge'])){
            $input['floor_incharge'] = $input['floor_incharge'];
        }else{
            $input = array_except($input,array('floor_incharge'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('role_user')->where('user_id',$id)->delete();

        foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }

        $reports_to = $request->input('reports_to');
        $floor_incharge = $request->input('floor_incharge');
        $type = $request->input('type');
        $check_report = $request->input('daily_report');
        $status = $request->input('status');
        $account_manager = $request->input('account_manager');

        $user->secondary_email=$request->input('semail');
        $user->daily_report = $check_report;
        $user->reports_to = $reports_to; 
        $user->floor_incharge = $floor_incharge;
        $user->status = $status;
        $user->account_manager = $account_manager;

        // Start Report Status

        if($check_report == 'Yes'){

            $cv_report = $request->input('cv_report');

            if(isset($cv_report) && $cv_report != '') {
                $user->cv_report = $cv_report;
            }
            else {
                $user->cv_report = NULL;
            }

            $interview_report = $request->input('interview_report');

            if(isset($interview_report) && $interview_report != '') {
                $user->interview_report = $interview_report;
            }
            else {
                $user->interview_report = NULL;
            }

            $lead_report = $request->input('lead_report');

            if(isset($lead_report) && $lead_report != '') {
                $user->lead_report = $lead_report;
            }
            else {
                $user->lead_report = NULL;
            }
        }
        else {

            $user->cv_report = NULL;
            $user->interview_report = NULL;
            $user->lead_report = NULL;
        }
        // End Report Status

        // Get first & last name

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        
        $user->first_name = $first_name;
        $user->last_name = $last_name;

        // Floor incharge or not

        $check_floor_incharge = $request->input('check_floor_incharge');
        $user->check_floor_incharge = $check_floor_incharge;

        $users = $user->save();

        //  If status is inactive then delete this user process and training
        if (isset($status) && $status == 'Inactive') {

            ProcessVisibleUser::where('user_id',$id)->delete();
            TrainingVisibleUser::where('user_id',$id)->delete();
        }
        if (isset($status) && $status == 'Active') {
            return redirect()->route('users.index')->with('success','User updated successfully. please add this user manually in training and process module.');
        }
        return redirect()->route('users.index')->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        return redirect()->route('users.index')->with('error','User can not be delete because associated with other modules.');

        $user_photo = \DB::table('users_doc')->select('file','user_id')->where('user_id','=',$id)->first();

        if(isset($user_photo)) {

            $photo_path = "uploads/users/" . $user_photo->user_id . "/photo/";

            $photo_files = glob($photo_path . "/*");

            foreach($photo_files as $file_nm) {
                if(is_file($file_nm)) {
                    unlink($file_nm);
                }
            }

            rmdir($photo_path);

            $path="uploads/users/" . $user_photo->user_id;
            $files=glob($path . "/*");

            foreach($files as $file_nm) {
                if(is_file($file_nm)) {
                    unlink($file_nm);
                }
            }

            $user_id = $user_photo->user_id;
            $path1="uploads/users/" . $user_id . "/";
            rmdir($path1);

            $user_doc = UsersDoc::where('user_id','=',$id)->delete();
            $user_other_info = UserOthersInfo::where('user_id','=',$id)->delete();
            ProcessVisibleUser::where('user_id',$id)->delete();
            TrainingVisibleUser::where('user_id',$id)->delete();
            UsersFamily::where('user_id',$id)->delete();
            $user = User::where('id','=',$id)->delete();
        }
        else {
            $user_other_info = UserOthersInfo::where('user_id','=',$id)->delete();
            ProcessVisibleUser::where('user_id',$id)->delete();
            TrainingVisibleUser::where('user_id',$id)->delete();
            UsersFamily::where('user_id',$id)->delete();
            $user = User::where('id','=',$id)->delete();
        }

        return redirect()->route('users.index')->with('success','User deleted successfully');
    }

    public function uploadSignatureImage(Request $request)
    {
        $user_id = \Auth::user()->id;

        $CKEditor = $request->input('CKEditor');
        $funcNum  = $request->input('CKEditorFuncNum');
        $message  = $url = '';

        if (Input::hasFile('upload'))
        {
            $file = Input::file('upload');
            if ($file->isValid())
            {
                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/uploads/users/'.$user_id.'/signature/', $filename);
                $url = url('uploads/users/'.$user_id.'/signature/' . $filename);
            }
            else
            {
                $message = 'An error occurred while uploading the file.';
            }
        }
        else
        {
            $message = 'No file uploaded.';
        }
        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
    }

    public function profileShow($user_id)
    {
        //superadmin
        $users =  \Auth::user();
        $loggedin_user_id = \Auth::user()->id;
        $userRole = $users->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        if ($isSuperAdmin || $isAccountant || $loggedin_user_id == $user_id) {        
            $dateClass = new Date();
            $user = array();

            // profile photo
            $user_doc_info = UsersDoc::getUserPhotoInfo($user_id);
            if(isset($user_doc_info)){
                $user['photo'] = $user_doc_info->file;
                $user['type'] = $user_doc_info->type;
            }
            else{
                $user['photo'] = '';
                $user['type'] = '';
            }

            $user_info = User::getProfileInfo($user_id);
            foreach($user_info as $key=>$value){
                $user['id'] = $user_id;
                $user['name'] = $value->name;
                $user['email'] = $value->email;
                $user['s_email'] = $value->secondary_email;
                $user['designation'] = $value->designation;
                $user['birth_date'] = $dateClass->changeYMDtoDMY($value->date_of_birth);
                $user['join_date'] = $dateClass->changeYMDtoDMY($value->date_of_joining);
                $user['salary'] = $value->fixed_salary;
                $user['performance_bonus'] = $value->performance_bonus;
                $user['total_salary'] = $value->total_salary;
                $user['acc_no'] = $value->acc_no;
                $user['bank_name'] = $value->bank_name;
                $user['branch_name'] = $value->branch_name;
                $user['ifsc_code'] = $value->ifsc_code;
                $user['user_full_name'] = $value->bank_full_name;
                $user['anni_date'] = $dateClass->changeYMDtoDMY($value->date_of_anniversary);
                $user['exit_date'] = $dateClass->changeYMDtoDMY($value->date_of_exit);
                $user['contact_number'] = $value->contact_number;
                $user['current_address'] = $value->current_address;
                $user['permanent_address'] = $value->permanent_address;
                $user['signature'] = $value->signature;
                $user['personal_email'] = $value->personal_email;
                $user['contact_no_official'] = $value->contact_no_official;
                $user['blood_group'] = $value->blood_group;
            }

            // User Family Details show
            $user_family = UsersFamily::getAllFamilyDetailsofUser($user_id);

            $userModel = new User();
            $users_upload_type = $userModel->users_upload_type;
            $j=0;
            $user['doc'] = array();
            $users_docs = \DB::table('users_doc')
                          ->select('users_doc.*')
                          ->where('user_id','=',$user_id)
                          /*->where('type','=','Others')*/
                          ->get();

            $utils = new Utils();
            foreach($users_docs as $key=>$value){
                $user['doc'][$j]['name'] = $value->name;
                $user['doc'][$j]['id'] = $value->id;
                $user['doc'][$j]['url'] = "../".$value->file;
                $user['doc'][$j]['size'] = $utils->formatSizeUnits($value->size);
                $user['doc'][$j]['type'] = $value->type;

                if (array_search($value->type, $users_upload_type)) {
                    unset($users_upload_type[array_search($value->type, $users_upload_type)]);
                }
                $j++;
            }

            $users_upload_type['Others'] = 'Others';

            return view('adminlte::users.myprofile',array('user' => $user),compact('isSuperAdmin','isAccountant','user_id','user_family','users_upload_type'));
        }
        else {
            return view('errors.403');
        }
    }
    public function editProfile($user_id)
    {
         //superadmin
        $users =  \Auth::user();
        $loggedin_user_id = \Auth::user()->id;
        $userRole = $users->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);
        $isOfficeAdmin = $user_obj::isOfficeAdmin($role_id);

        if ($isSuperAdmin || $isAccountant || $isOfficeAdmin || $loggedin_user_id == $user_id) {
            $dateClass = new Date();
            $user = array();

            // profile photo
            $user_doc_info = UsersDoc::getUserPhotoInfo($user_id);
            if(isset($user_doc_info)){
                $user['photo'] = $user_doc_info->file;
                $user['type'] = $user_doc_info->type;
                $user_photo_updated_date = date('Y-m-d',strtotime($user_doc_info->updated_at));
                $next_date =  date('Y-m-d',strtotime("$user_photo_updated_date  +30 days"));
            }
            else{
                $user['photo'] = '';
                $user['type'] = '';
                $next_date = date('Y-m-d');
            }

            $curr_date = date('Y-m-d');

            if($next_date == $curr_date || $next_date < $curr_date) {

                $user['edit_photo'] = '1';
            }
            else {

                $user['edit_photo'] = '0';
            }

            $user_info = User::getProfileInfo($user_id);

            //print_r($user_info);exit;

            foreach($user_info as $key=>$value){
                $user['user_id'] = $user_id;
                $user['name'] = $value->name;
                $user['email'] = $value->email;
                $user['semail'] = $value->secondary_email;
                $user['designation'] = $value->designation;
                $user['birth_date'] = $dateClass->changeYMDtoDMY($value->date_of_birth);
                $user['join_date'] = $dateClass->changeYMDtoDMY($value->date_of_joining);
                $user['salary'] = $value->fixed_salary;
                $user['performance_bonus'] = $value->performance_bonus;
                $user['total_salary'] = $value->total_salary;
                $user['acc_no'] = $value->acc_no;
                $user['bank_name'] = $value->bank_name;
                $user['branch_name'] = $value->branch_name;
                $user['ifsc_code'] = $value->ifsc_code;
                $user['user_full_name'] = $value->bank_full_name;
                $user['anni_date'] = $dateClass->changeYMDtoDMY($value->date_of_anniversary);
                $user['exit_date'] = $dateClass->changeYMDtoDMY($value->date_of_exit);
                $user['contact_number'] = $value->contact_number;
                $user['current_address'] = $value->current_address;
                $user['permanent_address'] = $value->permanent_address;
                $user['signature'] = $value->signature;
                //$user['official_gmail'] = $value->official_gmail;
                $user['personal_email'] = $value->personal_email;
                $user['contact_no_official'] = $value->contact_no_official;
                $user['blood_group'] = $value->blood_group;
                
                for ($i=1; $i <= 5 ; $i++) {
                    $users_family = UsersFamily::getFamilyDetailsofUser($user_id,$i);
                    if (isset($users_family) && $users_family != '') {
                        $user['name_'.$i] = $users_family->name;
                        $user['relationship_'.$i] = $users_family->relationship;
                        $user['occupation_'.$i] = $users_family->occupation;
                        $user['contact_no_'.$i] = $users_family->contact_no;
                    }
                    else {
                        $user['name_'.$i] = '';
                        $user['relationship_'.$i] = '';
                        $user['occupation_'.$i] = '';
                        $user['contact_no_'.$i] = '';
                    }
                }
            }

            $userModel = new User();
            $users_upload_type = $userModel->users_upload_type;

            $j=0;
            $user['doc'] = array();
            $users_docs = \DB::table('users_doc')
                          ->select('users_doc.*')
                          ->where('user_id','=',$user_id)
                          /*->where('type','=','Others')*/
                          ->get();

            $utils = new Utils();
            foreach($users_docs as $key=>$value){
                $user['doc'][$j]['name'] = $value->name;
                $user['doc'][$j]['id'] = $value->id;
                $user['doc'][$j]['url'] = "../".$value->file;
                $user['doc'][$j]['size'] = $utils->formatSizeUnits($value->size);
                $user['doc'][$j]['type'] = $value->type;

                if (array_search($value->type, $users_upload_type)) {
                    unset($users_upload_type[array_search($value->type, $users_upload_type)]);
                }
                $j++;
            }

            $users_upload_type['Others'] = 'Others';

            $gender = CandidateBasicInfo::getTypeArray();
            $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();

            return view('adminlte::users.editprofile',array('user' => $user),compact('isSuperAdmin','isAccountant','isOfficeAdmin','user_id','users_upload_type','gender','maritalStatus'));
        }
        else {
            return view('errors.403');
        }
    }

    public function profileStore($user_id,Request $request)
    {
        $dateClass = new Date();

        $upload_profile_photo = $request->file('image');
        //$user_id = \Auth::user()->id;
        $upload_documents = $request->file('upload_documents');

        $user_other_info = UserOthersInfo::getUserOtherInfo($user_id);

        if(isset($user_other_info) && $user_other_info->user_id == $user_id){

            $user_basic_info = User::find($user_id);
            $user_basic_info->name = Input::get('name');
            //$user_basic_info->email = Input::get('email');

            // Official gmail
            $user_basic_info->secondary_email = Input::get('semail');
            $user_basic_info->save();

            // User Otherinfo update
            $date_of_joining = Input::get('date_of_joining');
            $date_of_birth = Input::get('date_of_birth');
            $date_of_anniversary = Input::get('date_of_anni');
            $date_of_exit = Input::get('date_of_exit');
            $acc_no = Input::get('account_no');
            $ifsc_code = Input::get('ifsc');
            $contact_number = Input::get('contact');
            $current_address = Input::get('current_address');
            $permanent_address = Input::get('permanent_address');
            $signature = Input::get('signature');
            $personal_email = Input::get('personal_email');
            $contact_no_official = Input::get('contact_no_official');
            $blood_group = Input::get('blood_group');

            $users_otherinfo = UserOthersInfo::find($user_other_info->id);
            if (isset($date_of_joining) && $date_of_joining != '') {
                $users_otherinfo->date_of_joining = $dateClass->changeDMYtoYMD($date_of_joining);
            }
            else {
                $users_otherinfo->date_of_joining = NULL;
            }
            if (isset($date_of_birth) && $date_of_birth != '') {
                $users_otherinfo->date_of_birth = $dateClass->changeDMYtoYMD($date_of_birth);
            }
            else {
                $users_otherinfo->date_of_birth = NULL;
            }
            if(isset($acc_no) && $acc_no!=''){
                $users_otherinfo->acc_no = $acc_no;
            }
            else{
                $users_otherinfo->acc_no = '';
            }
            if(isset($ifsc_code) && $ifsc_code!=''){
                $users_otherinfo->ifsc_code = $ifsc_code;
            }
            else{
                $users_otherinfo->ifsc_code = '';
            }
            $users_otherinfo->bank_name = Input::get('bank_name');
            $users_otherinfo->branch_name = Input::get('branch_name');
            $users_otherinfo->bank_full_name = Input::get('user_full_name');
            if (isset($date_of_anniversary) && $date_of_anniversary != '') {
                $users_otherinfo->date_of_anniversary = $dateClass->changeDMYtoYMD($date_of_anniversary);
            }
            else {
                $users_otherinfo->date_of_anniversary = NULL;
            }
            if (isset($date_of_exit) && $date_of_exit != '') {
                $users_otherinfo->date_of_exit = $dateClass->changeDMYtoYMD($date_of_exit);
            }
            else {
                $users_otherinfo->date_of_exit = NULL;
            }
            $users_otherinfo->fixed_salary = Input::get('fixed_salary');
            $users_otherinfo->performance_bonus = Input::get('performance_bonus');
            $users_otherinfo->total_salary = Input::get('total_salary');
            $users_otherinfo->contact_number = $contact_number;
            $users_otherinfo->current_address = $current_address;
            $users_otherinfo->permanent_address = $permanent_address;
            $users_otherinfo->signature = $signature;
            $users_otherinfo->personal_email = $personal_email;
            $users_otherinfo->contact_no_official = $contact_no_official;
            $users_otherinfo->blood_group = $blood_group;
            $users_otherinfo->save();

            // User Family Details update
            // delete previous data
            $user_family_delete = UsersFamily::where('user_id',$user_id)->delete();

            // data store
            for ($i=1; $i <=5 ; $i++) {
                $name = Input::get('name_'.$i);
                $relationship = Input::get('relationship_'.$i);
                $occupation = Input::get('occupation_'.$i);
                $contact_no = Input::get('contact_no_'.$i);

                if (isset($name) && $name != '') {
                    $users_family = new UsersFamily();
                    $users_family->user_id = $user_id;
                    $users_family->name = $name;
                    $users_family->relationship = $relationship;
                    $users_family->occupation = $occupation;
                    $users_family->contact_no = $contact_no;
                    $users_family->family_id = $i;
                    $users_family->save();
                }
            }

            // stored photo
            $user_photo_info = UsersDoc::getUserPhotoInfo($user_id);
            if (isset($upload_profile_photo) && $upload_profile_photo->isValid()){
            
                $file_name = $upload_profile_photo->getClientOriginalName();
                $file_extension = $upload_profile_photo->getClientOriginalExtension();
                $file_realpath = $upload_profile_photo->getRealPath();
                $file_size = $upload_profile_photo->getSize();

                $path = "uploads/users/" . $user_id . '/photo';

                $files = glob($path . "/*");
                foreach($files as $file){
                    if(is_file($file)){
                        unlink($file);
                    }
                }

                $dir = 'uploads/users/' . $user_id . '/photo/';
                if (!file_exists($dir) && !is_dir($dir)) {
                    mkdir($dir, 0777, true);
                    chmod($dir, 0777);
                }

                $upload_profile_photo->move($dir, $file_name);

                $file_path = $dir . $file_name;

                if(isset($user_photo_info)){
                    $users_doc = UsersDoc::find($user_photo_info->id);
                    $users_doc->user_id = $user_id;
                    $users_doc->file = $file_path;
                    $users_doc->name = $file_name;
                    $users_doc->size = $file_size;
                    $users_doc->type = "Photo";
                    $users_doc->save();
                }
                else{
                    $users_doc = new UsersDoc();
                    $users_doc->user_id = $user_id;
                    $users_doc->file = $file_path;
                    $users_doc->name = $file_name;
                    $users_doc->size = $file_size;
                    $users_doc->type = "Photo";
                    $users_doc->save();
                }
            }

            //stored others documents
            if (isset($upload_documents) && sizeof($upload_documents) > 0){

                $users_upload_type = Input::get('users_upload_type');

                foreach ($upload_documents as $k => $v) {
                    if (isset($v) && $v->isValid()) {
                        // echo "here";
                        $file_name = $v->getClientOriginalName();
                        $file_extension = $v->getClientOriginalExtension();
                        $file_realpath = $v->getRealPath();
                        $file_size = $v->getSize();

                        $dir = 'uploads/users/' . $user_id . '/';

                        if (!file_exists($dir) && !is_dir($dir)) {
                            mkdir($dir, 0777, true);
                            chmod($dir, 0777);
                        }
                        $v->move($dir, $file_name);

                        $file_path = $dir . $file_name;

                        $users_doc = new UsersDoc();
                        $users_doc->user_id = $user_id;
                        $users_doc->file = $file_path;
                        $users_doc->name = $file_name;
                        $users_doc->size = $file_size;
                        //$users_doc->type = "Others";
                        $users_doc->type = $users_upload_type;
                        $users_doc->save();
                    }
                }
            }
        }
        else{

            $user_basic_info = User::find($user_id);
            $user_basic_info->name = Input::get('name');
            //$user_basic_info->email = Input::get('email');

            // Official gmail
            $user_basic_info->secondary_email = Input::get('semail');
            $user_basic_info->save();

            // User Otherinfo store
            $date_of_joining = Input::get('date_of_joining');
            $date_of_birth = Input::get('date_of_birth');
            $date_of_anniversary = Input::get('date_of_anni');
            $date_of_exit = Input::get('date_of_exit');
            $acc_no = Input::get('account_no');
            $ifsc_code = Input::get('ifsc');
            $contact_number = Input::get('contact');
            $current_address = Input::get('current_address');
            $permanent_address = Input::get('permanent_address');
            $signature = Input::get('signature');
            $personal_email = Input::get('personal_email');
            $contact_no_official = Input::get('contact_no_official');
            $blood_group = Input::get('blood_group');

            $users_otherinfo= new UserOthersInfo;
            $users_otherinfo->user_id = $user_id;
            if (isset($date_of_joining) && $date_of_joining != '') {
                $users_otherinfo->date_of_joining = $dateClass->changeDMYtoYMD($date_of_joining);
            }
            else {
                $users_otherinfo->date_of_joining = NULL;
            }
            if (isset($date_of_birth) && $date_of_birth != '') {
                $users_otherinfo->date_of_birth = $dateClass->changeDMYtoYMD($date_of_birth);
            }
            else {
                $users_otherinfo->date_of_birth = NULL;
            }
            if(isset($acc_no) && $acc_no!=''){
                $users_otherinfo->acc_no = $acc_no;
            }
            else{
                $users_otherinfo->acc_no = '';
            }
            if(isset($ifsc_code) && $ifsc_code!=''){
                $users_otherinfo->ifsc_code = $ifsc_code;
            }
            else{
                $users_otherinfo->ifsc_code = '';
            }
            $users_otherinfo->bank_name = Input::get('bank_name');
            $users_otherinfo->branch_name = Input::get('branch_name');
            $users_otherinfo->bank_full_name = Input::get('user_full_name');
            if (isset($date_of_anniversary) && $date_of_anniversary != '') {
                $users_otherinfo->date_of_anniversary = $dateClass->changeDMYtoYMD($date_of_anniversary);
            }
            else {
                $users_otherinfo->date_of_anniversary = NULL;
            }
            if (isset($date_of_exit) && $date_of_exit != '') {
                $users_otherinfo->date_of_exit = $dateClass->changeDMYtoYMD($date_of_exit);
            }
            else {
                $users_otherinfo->date_of_exit = NULL;
            }
            $users_otherinfo->fixed_salary = Input::get('fixed_salary');
            $users_otherinfo->contact_number = $contact_number;
            $users_otherinfo->current_address = $current_address;
            $users_otherinfo->permanent_address = $permanent_address;
            $users_otherinfo->signature = $signature;
            $users_otherinfo->personal_email = $personal_email;
            $users_otherinfo->contact_no_official = $contact_no_official;
            $users_otherinfo->blood_group = $blood_group;
            $users_otherinfo->save();

            // Users Family data store
            for ($i=1; $i <=5 ; $i++) {

                $name = Input::get('name_'.$i);
                $relationship = Input::get('relationship_'.$i);
                $occupation = Input::get('occupation_'.$i);
                $contact_no = Input::get('contact_no_'.$i);

                if (isset($name) && $name != '') {
                    $users_family = new UsersFamily();
                    $users_family->user_id = $user_id;
                    $users_family->name = $name;
                    $users_family->relationship = $relationship;
                    $users_family->occupation = $occupation;
                    $users_family->contact_no = $contact_no;
                    $users_family->family_id = $i;
                    $users_family->save();
                }
            }

            // stored photo
            if (isset($upload_profile_photo) && $upload_profile_photo->isValid()){
                $file_name = $upload_profile_photo->getClientOriginalName();
                $file_extension = $upload_profile_photo->getClientOriginalExtension();
                $file_realpath = $upload_profile_photo->getRealPath();
                $file_size = $upload_profile_photo->getSize();

                $dir = 'uploads/users/' . $user_id . '/photo/';

                if (!file_exists($dir) && !is_dir($dir)) {
                    mkdir($dir, 0777, true);
                    chmod($dir, 0777);
                }
                $upload_profile_photo->move($dir, $file_name);

                $file_path = $dir . $file_name;

                $users_doc = new UsersDoc();
                $users_doc->user_id = $user_id;
                $users_doc->file = $file_path;
                $users_doc->name = $file_name;
                $users_doc->size = $file_size;
                $users_doc->type = "Photo";
                $users_doc->save();
            }
            //stores attachmensts
            if (isset($upload_documents) && sizeof($upload_documents) > 0){

                $users_upload_type = Input::get('users_upload_type');

                foreach ($upload_documents as $k => $v) {
                    if (isset($v) && $v->isValid()) {
                        // echo "here";
                        $file_name = $v->getClientOriginalName();
                        $file_extension = $v->getClientOriginalExtension();
                        $file_realpath = $v->getRealPath();
                        $file_size = $v->getSize();

                        $dir = 'uploads/users/' . $user_id . '/';

                        if (!file_exists($dir) && !is_dir($dir)) {
                            mkdir($dir, 0777, true);
                            chmod($dir, 0777);
                        }
                        $v->move($dir, $file_name);

                        $file_path = $dir . $file_name;

                        $users_doc = new UsersDoc();
                        $users_doc->user_id = $user_id;
                        $users_doc->file = $file_path;
                        $users_doc->name = $file_name;
                        $users_doc->size = $file_size;
                        //$users_doc->type = "Others";
                        $users_doc->type = $users_upload_type;
                        $users_doc->save();
                    }
                }
            }
        }

        //return redirect()->route('users.index')->with('success','Profile Updated Successfully.'); 

        return redirect()->route('users.myprofile',$user_id)->with('success','Profile Updated Successfully.'); 
    }

    public function Upload(Request $request)
    {
        $file = $request->file('file');
        $users_upload_type = Input::get('users_upload_type');

        $id = $request->id;

        if (isset($file) && $file->isValid()) 
        {
            $doc_name = $file->getClientOriginalName();
            $doc_filesize = filesize($file);

            $dir_name = "uploads/users/".$id."/";
            $others_doc_key = "uploads/users/".$id."/".$doc_name;

             if (!file_exists($dir_name)) 
            {
                mkdir("uploads/users/$id", 0777,true);
            }

            if(!$file->move($dir_name, $doc_name))
            {
                return false;
            }
            else
            {
                $users_doc = new UsersDoc();
                $users_doc->user_id = $id;
                $users_doc->file = $others_doc_key;
                $users_doc->name = $doc_name;
                $users_doc->size = $doc_filesize;
                //$users_doc->type = "Others";
                $users_doc->type = $users_upload_type;
                $users_doc->save();
            }
        }

        return redirect()->route('users.myprofile',$id)->with('success','Attachment Uploaded Successfully.'); 
    }
    public function attachmentsDestroy($docid)
    {
        $user_id = \Auth::user()->id;

        $doc_attach = \DB::table('users_doc')
        ->select('users_doc.*')
        ->where('id','=',$docid)
        // ->where('type','=','Others')
        ->first();

        if(isset($doc_attach))
        {
            $path="uploads/users/" . $doc_attach->user_id . "/" . $doc_attach->name;
            unlink($path);

            $id = $doc_attach->user_id;

            $doc = UsersDoc::where('id','=',$docid)->delete();

        }

        return redirect()->route('users.myprofile',$user_id)->with('success','Attachment Deleted Successfully.'); 
    }

    /*public function userLeaveAdd()
    {
        $leave_type = UserLeave::getLeaveType();
        $leave_category = UserLeave::getLeaveCategory();

        return view('adminlte::users.leave',compact('leave_type','leave_category'));
    }

    public function leaveStore(Request $request)
    {
         $user_id = \Auth::user()->id;
         $dateClass = new Date();

         $user_leave = new UserLeave();

         $user_leave->user_id = $user_id;
         $user_leave->subject = Input::get('subject');
         $user_leave->from_date = $dateClass->changeDMYtoYMD(Input::get('from_date'));
         $user_leave->to_date = $dateClass->changeDMYtoYMD(Input::get('to_date'));
         $user_leave->type_of_leave = Input::get('leave_type');
         $user_leave->category = Input::get('leave_category');
         $user_leave->message = "Kindly Approved My Leave " . "From " . $user_leave->from_date . " To " . $user_leave->to_date . " " .Input::get('leave_msg');
         $user_leave->status = '0';
         $user_leave->save();

         $superadmin_userid = getenv('SUPERADMINUSERID');
         $floor_incharge_id = User::getFloorInchargeById($user_id);
         $reports_to_id = User::getReportsToById($user_id);

         $superadmin_secondary_email=User::getUserSecondaryEmailById($superadmin_userid);
         $floor_incharge_secondary_email = User::getUserSecondaryEmailById($floor_incharge_id);
         $reports_to_secondary_email = User::getUserSecondaryEmailById($reports_to_id);

         $cc_users_array = array($floor_incharge_secondary_email,$superadmin_secondary_email);

         $module = "Leave";
         $sender_name = $user_id;
         $to = $reports_to_secondary_email;
         $cc = implode(",",$cc_users_array);
         $subject = $user_leave->subject;
         $body_message = $user_leave->message;
         $module_id = $user_leave->id;

         event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));

         return redirect()->route('users.leave')->with('success',' Successfully');
    }*/

    public function testEmail(){

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        //$input['to'] = $user_email;
        $input['app_url'] = $app_url;
        $input['to'] = 'saloni@trajinfotech.com';

        \Mail::send('adminlte::emails.sample', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('test');
        });
    }

    public function UserAttendanceAdd(){

        $users = User::getAllUsers();
        $type = UsersLog::getattendancetype();

        return view('adminlte::users.attendanceadd',compact('users','type'));
    }

    public function UserAttendanceStore(Request $request){

        $dateClass = new Date();
        $user = $request->users;
        $type = $request->type;
        $date_time = $request->date_time;
        $date_convert = $dateClass->changeDMYHMStoYMDHMS($date_time);

        $dt = new \DateTime($date_convert, new \DateTimeZone('Asia/Kolkata'));
        $dt->setTimeZone(new \DateTimeZone('UTC'));
        $final_date = $dt->format("Y-m-d H:i:s") ;

        $date_array = explode(" ", $final_date);
        $date = $date_array[0];
        $time = $date_array[1];

        $users_log= new UsersLog();
        $users_log->user_id = $user;
        $users_log->date = $date;
        $users_log->time = $time;
        $users_log->type = $type;
        $users_log->created_at = $final_date;
        $users_log->updated_at = $final_date;
        $users_log->save();

        return redirect()->route('users.attendance')->with('success','Added Successfully');
    }
}
