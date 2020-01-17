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
        $permissions = New_Permissions::getAllPermissionsDetails();
        $modules = Module::getModules();
        $module_ids_array = array();
       
        return view('adminlte::new_role.create',compact('permissions','modules','module_ids_array'));
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
        $modules = Module::getModules();

        $rolePermissions = New_PermissionRole::getPermissionsByRoleID($id);

        $module_ids_array = array();
        $i=0;

        foreach ($rolePermissions as $key => $value) {
            $module_ids_array[$i] = $value['module_id'];
            $i++;       
        }

        $module_ids_array = array_unique($module_ids_array);
        return view('adminlte::new_role.edit',compact('role','modules','module_ids_array'));
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
    	$module_id = $_GET['module_id'];
    	$permissions = New_Permissions::getPermissionsByModuleID($module_id);

    	return $permissions;
    }

    public function getPermissionsByRoleID()
    {
        $module_ids = $_GET['module_selected_items'];
        $role_id = $_GET['role_id'];

        $permissions = New_Permissions::getPermissionsByModuleIDArray($module_ids);
        $rolePermissions = New_PermissionRole::getPermissionsByRoleID($role_id);

        $selected_permissions = array();
        $i=0;

        foreach ($rolePermissions as $key => $value) {
            $selected_permissions[$i] = $value['permission_id'];
            $i++;       
        }

        $data = array();
        $j=0;

        foreach ($permissions as $key => $value)
        {
            if(in_array($value['id'], $selected_permissions))
            {
                $data[$j]['id'] = $value['id'];
                $data[$j]['module_id'] = $value['module_id'];
                $data[$j]['module_name'] = $value['module_name'];
                $data[$j]['checked'] = '1';
                $data[$j]['display_name'] = $value['display_name'];
                $data[$j]['description'] = $value['description'];
            }
            else
            {
                $data[$j]['id'] = $value['id'];
                $data[$j]['module_id'] = $value['module_id'];
                $data[$j]['module_name'] = $value['module_name'];
                $data[$j]['checked'] = '0';
                $data[$j]['display_name'] = $value['display_name'];
                $data[$j]['description'] = $value['description'];
            }
            $j++;
        }
      
        return $data;exit;
    }
}
