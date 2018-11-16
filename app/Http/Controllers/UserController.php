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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->get();
        return view('adminlte::users.index',compact('data'))
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

        $roles = Role::pluck('display_name','id')->toArray();
        $reports_to = User::getUserArray($user_id);
        $reports_to = array_fill_keys(array(0),'Select Reports to')+$reports_to;

        $floor_incharge = User::getAllUsers();
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
        $status = $request->input('status');
        $account_manager = $request->input('account_manager');
        //print_r($account_manager);exit;

        $user->secondary_email=$request->input('semail');
        $user->reports_to = $reports_to;
        $user->floor_incharge = $floor_incharge;
        $user->status = $status;
        $user->account_manager = $account_manager;
        $users = $user->save();

       /* if(isset($user) && sizeof($user) > 0){
            if(isset($type)){
                $user->type = $type;
            }
        }*/

     

        return redirect()->route('users.index')
            ->with('success','User created successfully');

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
        $roles = Role::pluck('display_name','id');

        $reports_to = User::getUserArray($id);
        $reports_to = array_fill_keys(array(0),'Select Reports to')+$reports_to;

        $floor_incharge = User::getAllUsers();
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
        $status = $request->input('status');
        $account_manager = $request->input('account_manager');

        $user->secondary_email=$request->input('semail');
        $user->reports_to = $reports_to; 
        $user->floor_incharge = $floor_incharge;
        $user->status = $status;
        $user->account_manager = $account_manager;
        $users = $user->save();    

       /* $user = User::find($id);
        if(isset($user) && sizeof($user) > 0){
            if(isset($type)){
                $user->type = $type;
            }
        }*/


  

        return redirect()->route('users.index')
            ->with('success','User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
      $user_photo = \DB::table('users_doc')->select('file','user_id')->where('user_id','=',$id)->first();

        if(isset($user_photo))
        {
            $path="uploads/users/" . $user_photo->user_id;
            $files=glob($path . "/*");

            foreach($files as $file_nm)
            {
                if(is_file($file_nm))
                {
                        unlink($file_nm);
                }
            }

            $user_id = $user_photo->user_id;
            $path1="uploads/users/" . $user_id . "/";
            rmdir($path1);

            $user_doc = UsersDoc::where('user_id','=',$id)->delete();
            $user_other_info = UserOthersInfo::where('user_id','=',$id)->delete();
            $user = User::where('id','=',$id)->delete();
        }
        else
        {
            $user_other_info = UserOthersInfo::where('user_id','=',$id)->delete();
            $user = User::where('id','=',$id)->delete();
        }

        //User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success','User deleted successfully');
    }

    public function profileShow()
    {
        //superadmin
        $users =  \Auth::user();
        $userRole = $users->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $dateClass = new Date();
        $user_id = \Auth::user()->id;
        $user = array();

        // profile photo

        $user_doc_info = UsersDoc::getUserPhotoInfo($user_id);

        if(isset($user_doc_info))
        {
            $user['photo'] = $user_doc_info->file;
            $user['type'] = $user_doc_info->type;
        }
        else
        {
            $user['photo'] = '';
            $user['type'] = '';
        }

        $user_info = User::getProfileInfo($user_id);
        
        foreach($user_info as $key=>$value)
        {
            $user['id'] = $user_id;
            $user['name'] = $value->name;
            $user['email'] = $value->email;
            $user['s_email'] = $value->secondary_email;
            $user['designation'] = $value->designation;
            $user['birth_date'] = $dateClass->changeYMDtoDMY($value->date_of_birth);
            $user['join_date'] = $dateClass->changeYMDtoDMY($value->date_of_joining);
            $user['salary'] = $value->fixed_salary;
            $user['acc_no'] = $value->acc_no;
            $user['bank_name'] = $value->bank_name;
            $user['branch_name'] = $value->branch_name;
            $user['ifsc_code'] = $value->ifsc_code;
            $user['user_full_name'] = $value->bank_full_name;

            $user['anni_date'] = $dateClass->changeYMDtoDMY($value->date_of_anniversary);
            $user['exit_date'] = $dateClass->changeYMDtoDMY($value->date_of_exit);
            $user['spouse_name'] = $value->spouse_name;
            $user['spouse_profession'] = $value->spouse_profession;
            $user['spouse_contact_number'] = $value->spouse_contact_number;

            $user['father_name'] = $value->father_name;
            $user['father_profession'] = $value->father_profession;
            $user['father_contact_number'] = $value->father_contact_number;

            $user['mother_name'] = $value->mother_name;
            $user['mother_profession'] = $value->mother_profession;
            $user['mother_contact_number'] = $value->mother_contact_number;
        }

        $j=0;

        $user['doc'] = array();

        $users_docs = \DB::table('users_doc')
                      ->select('users_doc.*')
                      ->where('user_id','=',$user_id)
                      ->where('type','=','Others')
                      ->get();

        $utils = new Utils();

        foreach($users_docs as $key=>$value)
        {
            $user['doc'][$j]['name'] = $value->name;
            $user['doc'][$j]['id'] = $value->id;
            $user['doc'][$j]['url'] = "../".$value->file;
            $user['doc'][$j]['size'] = $utils->formatSizeUnits($value->size);
            $j++;
        }

        return view('adminlte::users.myprofile',array('user' => $user),compact('isSuperAdmin','isAccountant'));
    }
    public function editProfile()
    {
         //superadmin
        $users =  \Auth::user();
        $userRole = $users->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $dateClass = new Date();
        $user_id = \Auth::user()->id;
        $user = array();

        // profile photo

        $user_doc_info = UsersDoc::getUserPhotoInfo($user_id);

        if(isset($user_doc_info))
        {
            
            $user['photo'] = $user_doc_info->file;
            $user['type'] = $user_doc_info->type;
        }
        else
        {
            
            $user['photo'] = '';
            $user['type'] = '';
        }

        $user_info = User::getProfileInfo($user_id);
        
        foreach($user_info as $key=>$value)
        {
            $user['user_id'] = $user_id;
            $user['name'] = $value->name;
            $user['email'] = $value->email;
            $user['s_email'] = $value->secondary_email;
            $user['designation'] = $value->designation;
            $user['birth_date'] = $dateClass->changeYMDtoDMY($value->date_of_birth);
            $user['join_date'] = $dateClass->changeYMDtoDMY($value->date_of_joining);
            $user['salary'] = $value->fixed_salary;
            $user['acc_no'] = $value->acc_no;
            $user['bank_name'] = $value->bank_name;
            $user['branch_name'] = $value->branch_name;
            $user['ifsc_code'] = $value->ifsc_code;
            $user['user_full_name'] = $value->bank_full_name;

            $user['anni_date'] = $dateClass->changeYMDtoDMY($value->date_of_anniversary);
            $user['exit_date'] = $dateClass->changeYMDtoDMY($value->date_of_exit);
            $user['spouse_name'] = $value->spouse_name;
            $user['spouse_profession'] = $value->spouse_profession;
            $user['spouse_contact_number'] = $value->spouse_contact_number;

            $user['father_name'] = $value->father_name;
            $user['father_profession'] = $value->father_profession;
            $user['father_contact_number'] = $value->father_contact_number;

            $user['mother_name'] = $value->mother_name;
            $user['mother_profession'] = $value->mother_profession;
            $user['mother_contact_number'] = $value->mother_contact_number;
        }

        $j=0;

        $user['doc'] = array();

        $users_docs = \DB::table('users_doc')
                      ->select('users_doc.*')
                      ->where('user_id','=',$user_id)
                      ->where('type','=','Others')
                      ->get();

        $utils = new Utils();

        foreach($users_docs as $key=>$value)
        {
            $user['doc'][$j]['name'] = $value->name;
            $user['doc'][$j]['id'] = $value->id;
            $user['doc'][$j]['url'] = "../".$value->file;
            $user['doc'][$j]['size'] = $utils->formatSizeUnits($value->size);
            $j++;
        }

        return view('adminlte::users.editprofile',array('user' => $user),compact('isSuperAdmin','isAccountant'));
    }

    public function profileStore(Request $request)
    {
        $upload_profile_photo = $request->file('image');

        $user_id = \Auth::user()->id;

        $upload_documents = $request->file('upload_documents');

        $user_other_info = UserOthersInfo::getUserOtherInfo($user_id);

        if(isset($user_other_info) && $user_other_info->user_id == $user_id)
        {
            $user_basic_info = User::find($user_id);

            $user_basic_info->name = Input::get('name');
            $user_basic_info->secondary_email = Input::get('semail');

            $user_basic_info->save();

            $dateClass = new Date();

            $users_otherinfo = UserOthersInfo::find($user_other_info->id);

            //$users_otherinfo->user_id = $user_id;

            $users_otherinfo->date_of_joining = $dateClass->changeDMYtoYMD(Input::get('date_of_joining'));
            $users_otherinfo->date_of_birth = $dateClass->changeDMYtoYMD(Input::get('date_of_birth'));
            $users_otherinfo->fixed_salary = Input::get('salary');

            $acc_no = Input::get('account_no');

            if(isset($acc_no) && $acc_no!='')
            {
                $users_otherinfo->acc_no = $acc_no;
            }
            else
            {
                $users_otherinfo->acc_no = '0';
            }
           
            $ifsc_code = Input::get('ifsc');

            if(isset($ifsc_code) && $ifsc_code!='')
            {
                $users_otherinfo->ifsc_code = $ifsc_code;
            }
            else
            {
                $users_otherinfo->ifsc_code = '0';
            }
            
            $users_otherinfo->bank_name = Input::get('bank_name');
            $users_otherinfo->branch_name = Input::get('branch_name');
            $users_otherinfo->bank_full_name = Input::get('user_full_name');

            $users_otherinfo->date_of_anniversary = $dateClass->changeDMYtoYMD(Input::get('date_of_anni'));
            $users_otherinfo->date_of_exit = $dateClass->changeDMYtoYMD(Input::get('date_of_exit'));

            $users_otherinfo->spouse_name = Input::get('spouse_name');
            $users_otherinfo->spouse_profession = Input::get('spouse_profession');
            $users_otherinfo->spouse_contact_number = Input::get('spouse_contact_no');

            $users_otherinfo->father_name = Input::get('father_name');
            $users_otherinfo->father_profession = Input::get('father_profession');
            $users_otherinfo->father_contact_number = Input::get('father_contact_no');

            $users_otherinfo->mother_name = Input::get('mother_name');
            $users_otherinfo->mother_profession = Input::get('mother_profession');
            $users_otherinfo->mother_contact_number = Input::get('mother_contact_no');
            $users_otherinfo->save();

            // stored photo

            $user_photo_info = UsersDoc::getUserPhotoInfo($user_id);

            if (isset($upload_profile_photo) && $upload_profile_photo->isValid())
            {
            
                $file_name = $upload_profile_photo->getClientOriginalName();
                $file_extension = $upload_profile_photo->getClientOriginalExtension();
                $file_realpath = $upload_profile_photo->getRealPath();
                $file_size = $upload_profile_photo->getSize();

                $path="uploads/users/" . $user_id . '/photo';

                $files=glob($path . "/*");

                foreach($files as $file)
                {
                    if(is_file($file))
                    {
                        unlink($file);
                    }
                }
          
                $dir = 'uploads/users/' . $user_id . '/photo/';

                if (!file_exists($dir) && !is_dir($dir)) 
                {
                    mkdir($dir, 0777, true);
                    chmod($dir, 0777);
                }
                $upload_profile_photo->move($dir, $file_name);

                $file_path = $dir . $file_name;

                if(isset($user_photo_info))
                {
                    $users_doc = UsersDoc::find($user_photo_info->id);
                    $users_doc->user_id = $user_id;
                    $users_doc->file = $file_path;
                    $users_doc->name = $file_name;
                    $users_doc->size = $file_size;
                    $users_doc->type = "Photo";
                    $users_doc->save();
                }
                else
                {
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

            if (isset($upload_documents) && sizeof($upload_documents) > 0)
            {
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
                        $users_doc->type = "Others";
                        $users_doc->save();
                    }

                }
            }
        }
        else
        {
            $user_basic_info = User::find($user_id);

            $user_basic_info->name = Input::get('name');
            $user_basic_info->secondary_email = Input::get('semail');

            $user_basic_info->save();

            $dateClass = new Date();

            $users_otherinfo= new UserOthersInfo;

            $users_otherinfo->user_id = $user_id;
            $users_otherinfo->date_of_joining = $dateClass->changeDMYtoYMD(Input::get('date_of_joining'));
            $users_otherinfo->date_of_birth = $dateClass->changeDMYtoYMD(Input::get('date_of_birth'));
            $users_otherinfo->fixed_salary = Input::get('salary');

            $acc_no = Input::get('account_no');

            if(isset($acc_no) && $acc_no!='')
            {
                $users_otherinfo->acc_no = $acc_no;
            }
            else
            {
                $users_otherinfo->acc_no = '0';
            }
           
            $ifsc_code = Input::get('ifsc');

            if(isset($ifsc_code) && $ifsc_code!='')
            {
                $users_otherinfo->ifsc_code = $ifsc_code;
            }
            else
            {
                $users_otherinfo->ifsc_code = '0';
            }

            $users_otherinfo->bank_name = Input::get('bank_name');
            $users_otherinfo->branch_name = Input::get('branch_name');
            $users_otherinfo->bank_full_name = Input::get('user_full_name');

            $users_otherinfo->date_of_anniversary = $dateClass->changeDMYtoYMD(Input::get('date_of_anni'));
            $users_otherinfo->date_of_exit = $dateClass->changeDMYtoYMD(Input::get('date_of_exit'));

            $users_otherinfo->spouse_name = Input::get('spouse_name');
            $users_otherinfo->spouse_profession = Input::get('spouse_profession');
            $users_otherinfo->spouse_contact_number = Input::get('spouse_contact_no');

            $users_otherinfo->father_name = Input::get('father_name');
            $users_otherinfo->father_profession = Input::get('father_profession');
            $users_otherinfo->father_contact_number = Input::get('father_contact_no');

            $users_otherinfo->mother_name = Input::get('mother_name');
            $users_otherinfo->mother_profession = Input::get('mother_profession');
            $users_otherinfo->mother_contact_number = Input::get('mother_contact_no');

            $users_otherinfo->save();

            // stored photo
            if (isset($upload_profile_photo) && $upload_profile_photo->isValid())
            {
            
                $file_name = $upload_profile_photo->getClientOriginalName();
                $file_extension = $upload_profile_photo->getClientOriginalExtension();
                $file_realpath = $upload_profile_photo->getRealPath();
                $file_size = $upload_profile_photo->getSize();

                $dir = 'uploads/users/' . $user_id . '/photo/';

                if (!file_exists($dir) && !is_dir($dir)) 
                {
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
            if (isset($upload_documents) && sizeof($upload_documents) > 0)
            {
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
                        $users_doc->type = "Others";
                        $users_doc->save();
                    }

                }
            }
        }

        return redirect()->route('users.editprofile')->with('success','Profile Updated Successfully'); 
    }

    public function Upload(Request $request)
    {
        $file = $request->file('file');

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
                $users_doc->type = "Others";
                $users_doc->save();
            }
        }

         return redirect()->route('users.myprofile')->with('success','Attachment Uploaded Successfully'); 
    }
    public function attachmentsDestroy($docid)
    {

        $doc_attach = \DB::table('users_doc')
        ->select('users_doc.*')
        ->where('id','=',$docid)
        ->where('type','=','Others')
        ->first();

        if(isset($doc_attach))
        {
            $path="uploads/users/" . $doc_attach->user_id . "/" . $doc_attach->name;
            unlink($path);

            $id = $doc_attach->user_id;

            $doc = UsersDoc::where('id','=',$docid)->delete();

        }

        return redirect()->route('users.myprofile')->with('success','Attachment Deleted Successfully'); 
    }

    public function userLeave()
    {
         $leave_type = UserOthersInfo::getLeaveType();
         $leave_category = UserOthersInfo::getLeaveCategory();
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
    }

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
