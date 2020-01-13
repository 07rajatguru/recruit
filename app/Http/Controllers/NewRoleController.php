<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\New_Role;
use App\New_Permissions;
use App\New_PermissionRole;
use App\User;
use App\Module;
use DB;

class NewRoleController extends Controller
{
    public function index()
    {
    	$roles = New_Role::orderBy('id','ASC')->get();
    	$count = sizeof($roles);

        return view('adminlte::new_role.index',compact('roles','count'));
    }

    public function create()
    {
        $action = 'add';
        $permissions = New_Permissions::get();
        $modules = Module::getModules();
        $module_ids_array = array();
       
        return view('adminlte::new_role.create',compact('permissions','action','modules','module_ids_array'));
    }

    public function store(Request $request)
    {
        $role = new New_Role();
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();

        if($role->save())
        {
            $role_id = $role->id;

            $permissions = $request->input('permission');
            if(isset($permissions) && sizeof($permissions)>0)
            {
                foreach ($permissions as $key => $value)
                {
                    $permissions_role = new New_PermissionRole();
                    $permissions_role->permission_id = $value;
                    $permissions_role->role_id = $role_id;
                    $permissions_role->save(); 
                }
            }
        }
        return redirect()->route('userrole.index')->with('success','Role Created Successfully.');
    }

    public function show($id)
    {
        $role = New_Role::find($id);
        $rolePermissions = New_Permissions::join("new_permission_role","new_permission_role.permission_id","=","new_permissions.id")
            ->where("new_permission_role.role_id",$id)
            ->get();

        return view('adminlte::new_role.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = New_Role::find($id);
        $action = 'edit';
        $modules = Module::getModules();

        $rolePermissions = New_PermissionRole::getPermissionsByRoleID($id);

        $module_ids_array = array();
        $permissions_ids_array = array();
        $i=0;

        foreach ($rolePermissions as $key => $value) {
            $module_ids_array[$i] = $value['module_id'];
            $permissions_ids_array[$i] = $value['permission_id'];
            $i++;       
        }

        $module_ids_array = array_unique($module_ids_array);
        $implode_module_ids_array = implode(",", $module_ids_array);
        $get_permissions = New_Permissions::getPermissionsByModuleID($implode_module_ids_array);

        return view('adminlte::new_role.edit',compact('role','action','modules','module_ids_array','permissions_ids_array','get_permissions'));
    }

    public function update(Request $request,$id)
    {
        $role = New_Role::find($id);
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();

        if($role->save())
        {
            $role_id = $role->id;

            $permissions = $request->input('permission');
            if(isset($permissions) && sizeof($permissions)>0)
            {
                DB::table("new_permission_role")->where("new_permission_role.role_id",$id)->delete();

                foreach ($permissions as $key => $value)
                {
                    $permissions_role = new New_PermissionRole();
                    $permissions_role->permission_id = $value;
                    $permissions_role->role_id = $role_id;
                    $permissions_role->save(); 
                }
            }
        }
        return redirect()->route('userrole.index')->with('success','Role Created Successfully.');
    }

    public function destroy($id)
    {
        DB::table("new_permission_role")->where('role_id',$id)->delete();
        DB::table("new_roles")->where('id',$id)->delete();

        return redirect()->route('userrole.index')
            ->with('success','Role Deleted Successfully.');
    }

    public function getPermissions()
    {
    	$module_ids = $_GET['arr'];
    	$permissions = New_Permissions::getPermissionsByModuleID($module_ids);

    	return $permissions;
    }
}
