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
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success','User deleted successfully');

    }

    public static function editProfile()
    {
        $dateClass = new Date();

        $user_id = \Auth::user()->id;

        $role_id = User::getRoleIdByUserId($user_id);

        $user = array();

        $user_info = \DB::table('users')
         ->leftjoin('role_user','role_user.user_id','=','users.id')
         ->leftjoin('roles','roles.id','=','role_user.role_id')
         ->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id')
         ->select('users.*','roles.display_name as designation','users_otherinfo.*')
         ->where('users.id' ,'=',$user_id)
         ->first();

        $name = $user_info->name;
        $email = $user_info->email;
        $s_email = $user_info->secondary_email;
        $designation = $user_info->designation;
        $birth_date = $dateClass->changeYMDtoDMY($user_info->date_of_birth);
        $join_date = $dateClass->changeYMDtoDMY($user_info->date_of_joining);
        $salary = $user_info->fixed_salary;
        $acc_no = $user_info->acc_no;
        $bank_name = $user_info->bank_name;
        $branch_name = $user_info->branch_name;
        $ifsc_code = $user_info->ifsc_code;
        $user_full_name = $user_info->bank_full_name;

        return view('adminlte::users.editprofile',compact('name','email','s_email','designation','birth_date','join_date','salary','acc_no','bank_name','branch_name','ifsc_code','user_full_name','user_id'));
    }

    public static function profileStore()
    {
        $user_id = \Auth::user()->id;

        $user_other_info = UserOthersInfo::getUserOtherInfo($user_id);

        if(isset($user_other_info) && $user_other_info->user_id == $user_id)
        {
            $dateClass = new Date();

            $users_otherinfo = UserOthersInfo::find($user_other_info->id);

            //$users_otherinfo->user_id = $user_id;

            $users_otherinfo->date_of_joining = $dateClass->changeDMYtoYMD(Input::get('date_of_joining'));
            $users_otherinfo->date_of_birth = $dateClass->changeDMYtoYMD(Input::get('date_of_birth'));
            $users_otherinfo->fixed_salary = Input::get('salary');
            $users_otherinfo->acc_no = Input::get('account_no');
            $users_otherinfo->bank_name = Input::get('bank_name');
            $users_otherinfo->branch_name = Input::get('branch_name');
            $users_otherinfo->ifsc_code = Input::get('ifsc');
            $users_otherinfo->bank_full_name = Input::get('user_full_name');

            $users_otherinfo->save();
        }
        else
        {
            $dateClass = new Date();

            $users_otherinfo= new UserOthersInfo;

            $users_otherinfo->user_id = $user_id;
            $users_otherinfo->date_of_joining = $dateClass->changeDMYtoYMD(Input::get('date_of_joining'));
            $users_otherinfo->date_of_birth = $dateClass->changeDMYtoYMD(Input::get('date_of_birth'));
            $users_otherinfo->fixed_salary = Input::get('salary');
            $users_otherinfo->acc_no = Input::get('account_no');
            $users_otherinfo->bank_name = Input::get('bank_name');
            $users_otherinfo->branch_name = Input::get('branch_name');
            $users_otherinfo->ifsc_code = Input::get('ifsc');
            $users_otherinfo->bank_full_name = Input::get('user_full_name');

            $users_otherinfo->save();
        }

        return redirect('/dashboard');
    }
}
