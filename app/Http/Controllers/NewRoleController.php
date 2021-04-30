<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\PermissionRole;
use App\User;
use App\Module;
use DB;
use App\Department;

class NewRoleController extends Controller
{
    public function index() {

    	$roles = Role::orderBy('id','ASC')->get();
    	$count = sizeof($roles);

        return view('adminlte::new_role.index',compact('roles','count'));
    }

    public function create() {

        $permissions = Permission::getAllPermissionsDetails();
        $modules = Module::getModules();
        $module_ids_array = array();
        
        $department_res = Department::orderBy('name','DESC')->get();
        $departments = array();

        if(sizeof($department_res) > 0) {
            foreach($department_res as $r) {
                $departments[$r->name] = $r->name;
            }
        }
        $department_name = '';
       
        return view('adminlte::new_role.create',compact('permissions','modules','module_ids_array','departments','department_name'));
    }

    public function store(Request $request) {

        $module_ids = $request->input('module_ids');

        if(isset($module_ids) && $module_ids != '') {
            $module_ids = implode(",", $module_ids);
        }

        $role = new Role();
        $role->module_ids = $module_ids;
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->department = $request->input('department');
        $role->save();

        if($role->save()) {

            $role_id = $role->id;

            $permissions = $request->input('permission');
            if(isset($permissions) && sizeof($permissions)>0) {

                foreach ($permissions as $key => $value) {

                    $permissions_role = new PermissionRole();
                    $permissions_role->permission_id = $value;
                    $permissions_role->role_id = $role_id;
                    $permissions_role->save(); 
                }
            }
        }
        return redirect()->route('userrole.index')->with('success','Role Created Successfully.');
    }

    public function show($id) {

        $role = Role::find($id);
        $rolePermissions = Permission::join("permission_role","permission_role.permission_id","=","permissions.id")->where("permission_role.role_id",$id)->get();

        return view('adminlte::new_role.show',compact('role','rolePermissions'));
    }

    public function edit($id) {

        $role = Role::find($id);
        $modules = Module::getModules();

        $rolePermissions = PermissionRole::getPermissionsByRoleID($id);

        $module_ids_array = array();
        $i=0;

        foreach ($rolePermissions as $key => $value) {
            $module_ids_array[$i] = $value['module_id'];
            $i++;       
        }

        $module_ids_array = array_unique($module_ids_array);

        $department_res = Department::orderBy('name','DESC')->get();
        $departments = array();

        if(sizeof($department_res) > 0) {
            foreach($department_res as $r) {
                $departments[$r->name] = $r->name;
            }
        }
        $department_name = $role->department;

        return view('adminlte::new_role.edit',compact('role','modules','module_ids_array','departments','department_name'));
    }

    public function update(Request $request,$id) {

        $module_ids = $request->input('module_ids');

        if(isset($module_ids) && $module_ids != '') {
            $module_ids = implode(",", $module_ids);
        }

        $role = Role::find($id);
        $role->module_ids = $module_ids;
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->department = $request->input('department');
        $role->save();

        if($role->save()) {

            $role_id = $role->id;
            $permissions = $request->input('permission');

            if(isset($permissions) && sizeof($permissions)>0) {

                DB::table("permission_role")->where("permission_role.role_id",$id)->delete();

                foreach ($permissions as $key => $value) {
                    
                    $permissions_role = new PermissionRole();
                    $permissions_role->permission_id = $value;
                    $permissions_role->role_id = $role_id;
                    $permissions_role->save(); 
                }
            }
        }
        return redirect()->route('userrole.index')->with('success','Role Updated Successfully.');
    }

    public function destroy($id) {

        DB::table("permission_role")->where('role_id',$id)->delete();
        DB::table("roles")->where('id',$id)->delete();

        return redirect()->route('userrole.index')->with('success','Role Deleted Successfully.');
    }

    public function getPermissions() {

    	$module_id = $_GET['module_id'];
    	$permissions = Permission::getPermissionsByModuleID($module_id);

    	return $permissions;
    }

    public function getPermissionsByRoleID() {

        $module_ids = $_GET['module_selected_items'];
        $role_id = $_GET['role_id'];

        $permissions = Permission::getPermissionsByModuleIDArray($module_ids);
        $rolePermissions = PermissionRole::getPermissionsByRoleID($role_id);

        $selected_permissions = array();
        $i=0;

        foreach ($rolePermissions as $key => $value) {
            $selected_permissions[$i] = $value['permission_id'];
            $i++;       
        }

        $data = array();
        $j=0;

        foreach ($permissions as $key => $value) {

            if(in_array($value['id'], $selected_permissions)) {
                $data[$j]['checked'] = '1';
            }
            else {
                $data[$j]['checked'] = '0';
            }
            
            $data[$j]['id'] = $value['id'];
            $data[$j]['module_id'] = $value['module_id'];
            $data[$j]['module_name'] = $value['module_name'];
            $data[$j]['display_name'] = $value['display_name'];
            $data[$j]['description'] = $value['description'];

            $j++;
        }
        return $data;exit;
    }

    public function userWiseModuleAjax() {

        $user_role_id = $_POST['user_role_id'];

        $module_user = Role::getModulesByUserRoleId($user_role_id);
        $module_total = Module::getAllModulesNameAjax();
        
        $module_hide = array();

        foreach ($module_total as $key => $value) {
            if (!in_array($value, $module_user)) {
                $module_hide[] = $value;
            }
        }

        $msg['module_user'] = $module_user;
        $msg['module_hide'] = $module_hide;
        $msg['module_total'] = $module_total;

        return json_encode($msg);exit;
    }

    public function rolewisePermissions() {

        $permissions = Permission::getAllPermissionsDetails();

        $roles = Role::getAllRolesDetails();

        if(isset($roles) && sizeof($roles) > 0) {

            foreach ($roles as $key => $value) {
                $roleswise[$value['id']] = PermissionRole::getPermissionsStringByRoleID($value['id']);
            }
        }

        return view('adminlte::new_role.view',compact('permissions','roles','roleswise'));
    }

    public function addRolePermissions() {

        $role_id = $_POST['role_id'];
        $permission_id = $_POST['permission_id'];
        $check = $_POST['check'];

        if($check == 'true') {

            $response = PermissionRole::checkExistorNot($role_id,$permission_id);

            if($response == '') {

                $permission_role = new PermissionRole();
                $permission_role->permission_id = $permission_id;
                $permission_role->role_id = $role_id;
                $permission_role->save();
            }
        }
        else {

            DB::table("permission_role")->where('permission_id',$permission_id)->where('role_id',$role_id)->delete();
        }
    }
}