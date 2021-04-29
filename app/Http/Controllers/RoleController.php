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

        $roles = Role::orderBy('id','ASC')->get();
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
        $departments = Department::get();
        return view('adminlte::roles.create',compact('permission','action','departments'));
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

        $role = Role::find($id);
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

        $departments = Department::get();

        return view('adminlte::roles.edit',compact('role','permission','rolePermissions','action','departments'));
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
}
