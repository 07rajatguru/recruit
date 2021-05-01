<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use DB;
use App\User;
use App\Department;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {

        $roles = Role::orderBy('id','ASC')
        ->leftjoin('department','department.id','=','roles.department')
        ->select('roles.*','department.name as department')->get();

        return view('adminlte::roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create() {

        $action = 'add';
        $permission = Permission::get();

        $department_res = Department::orderBy('name','DESC')->get();
        $departments = array();

        if(sizeof($department_res) > 0) {
            foreach($department_res as $r) {
                $departments[$r->id] = $r->name;
            }
        }
        $department_id = '';

        return view('adminlte::roles.create',compact('permission','action','departments','department_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $role = new Role();
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->department = $request->input('department');
        $role->save();

        $permissions = $request->input('permission');
        if(isset($permissions)) {
            foreach ($request->input('permission') as $key => $value) {
                $role->attachPermission($value);
            }
        }
        return redirect()->route('roles.index')->with('success','Role Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id) {

        $role = \DB::table('roles')
        ->leftjoin('department','department.id','=','roles.department')
        ->select('roles.*','department.name as department')->where('roles.id','=',$id)->first();

        $rolePermissions = Permission::join("permission_role","permission_role.permission_id","=","permissions.id")->where("permission_role.role_id",$id)->get();

        return view('adminlte::roles.show',compact('role','rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id) {

        $role = Role::find($id);
        $permission = Permission::get();
        $action = 'edit';
        $rolePermissions = DB::table("permission_role")->where("permission_role.role_id",$id)
        ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();

        $department_res = Department::orderBy('name','DESC')->get();
        $departments = array();

        if(sizeof($department_res) > 0) {
            foreach($department_res as $r) {
                $departments[$r->id] = $r->name;
            }
        }
        $department_id = $role->department;

        return view('adminlte::roles.edit',compact('role','permission','rolePermissions','action','departments','department_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id) {

        $this->validate($request, [
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $role = Role::find($id);
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->department = $request->input('department');
        $role->save();

        DB::table("permission_role")->where("permission_role.role_id",$id)->delete();

        $permissions = $request->input('permission');
        if(isset($permissions)) {
            foreach ($request->input('permission') as $key => $value) {
                $role->attachPermission($value);
            }

        }
        return redirect()->route('roles.index')->with('success','Role Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id) {

        DB::table("permission_role")->where('role_id',$id)->delete();
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')->with('success','Role Deleted Successfully.');
    }

    public function getRoles() {

        $department_id = $_GET['department_id'];
        $user_id = $_GET['user_id'];

        if (isset($user_id) && $user_id > 0) {
            $user = User::find($user_id);

            $user = \DB::table('users')
            ->leftjoin('role_user','role_user.user_id','=','users.id')
            ->select('role_user.role_id as role_id')
            ->where('users.id','=',$user_id)->first();

            $pre_role_id = $user->role_id;
        }
        else {
            $pre_role_id = 0;
        }

        $roles_res = Role::getRolesByDepartmentId($department_id);

        $data['pre_role_id'] = $pre_role_id;
        $data['roles_res'] = $roles_res;

        return json_encode($data);
    }
}