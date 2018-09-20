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
        $reports_to = array_fill_keys(array(''),'Select Reports to')+$reports_to;

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
       // print_r($status);exit;

        $user->secondary_email=$request->input('semail');
        $user->reports_to = $reports_to;
        $user->floor_incharge = $floor_incharge;
        $user->status = $status;
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
        $reports_to = array_fill_keys(array(''),'Select Reports to')+$reports_to;

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

        $user->secondary_email=$request->input('semail');
        $user->reports_to = $reports_to; 
        $user->floor_incharge = $floor_incharge;
        $user->status = $status;
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
        $dateClass = new Date();

        $user_id = \Auth::user()->id;

        $role_id = User::getRoleIdByUserId($user_id);

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
            //$user['id'] = $value->doc_id;
            $user['user_id'] = $user_id;
            $user['name'] = $value->name;
            $user['email'] = $value->email;
            $user['s_email'] = $value->secondary_email;
            $user['designation'] = $value->designation;
            //$user['photo'] = $value->file;
            //$user['type'] = $value->type;
            $user['birth_date'] = $dateClass->changeYMDtoDMY($value->date_of_birth);
            $user['join_date'] = $dateClass->changeYMDtoDMY($value->date_of_joining);
            $user['salary'] = $value->fixed_salary;
            $user['acc_no'] = $value->acc_no;
            $user['bank_name'] = $value->bank_name;
            $user['branch_name'] = $value->branch_name;
            $user['ifsc_code'] = $value->ifsc_code;
            $user['user_full_name'] = $value->bank_full_name;
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

        return view('adminlte::users.myprofile',array('user' => $user));
    }
    public function editProfile()
    {
        $dateClass = new Date();

        $user_id = \Auth::user()->id;

        $role_id = User::getRoleIdByUserId($user_id);

        $user = array();

        // profile photo

        $user_doc_info = UsersDoc::getUserPhotoInfo($user_id);

        if(isset($user_doc_info))
        {
            $user['id'] = $user_doc_info->id;
            $user['photo'] = $user_doc_info->file;
            $user['type'] = $user_doc_info->type;
        }
        else
        {
            $user['id'] = '';
            $user['photo'] = '';
            $user['type'] = '';
        }

        $user_info = User::getProfileInfo($user_id);
        
        foreach($user_info as $key=>$value)
        {
            //$user['id'] = $value->photoid;
            $user['user_id'] = $user_id;
            $user['name'] = $value->name;
            $user['email'] = $value->email;
            $user['s_email'] = $value->secondary_email;
            $user['designation'] = $value->designation;
            //$user['photo'] = $value->file;
            //$user['type'] = $value->type;
            $user['birth_date'] = $dateClass->changeYMDtoDMY($value->date_of_birth);
            $user['join_date'] = $dateClass->changeYMDtoDMY($value->date_of_joining);
            $user['salary'] = $value->fixed_salary;
            $user['acc_no'] = $value->acc_no;
            $user['bank_name'] = $value->bank_name;
            $user['branch_name'] = $value->branch_name;
            $user['ifsc_code'] = $value->ifsc_code;
            $user['user_full_name'] = $value->bank_full_name;
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

        return view('adminlte::users.editprofile',array('user' => $user));
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

        return redirect()->route('users.editprofile')->with('success','Information Updates Successfully'); 
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
}
