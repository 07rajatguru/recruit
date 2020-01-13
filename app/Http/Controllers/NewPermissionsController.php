<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\New_Permissions;
use App\Module;

class NewPermissionsController extends Controller
{
    public function index()
    {
    	$permissions = New_Permissions::getAllPermissionsDetails();
    	$count = sizeof($permissions);

    	return view('adminlte::new_permissions.index',compact('permissions','count'));
    }

    public function create()
    {
    	$action = 'add';
    	$modules = Module::getModules();
    	$selected_module = '';

        return view('adminlte::new_permissions.create', compact('action','modules','selected_module'));
    }

    public function store(Request $request)
    {
    	$permission = new New_Permissions();
    	$permission->module_id = $request->input('module_id');
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permissionStored = $permission->save();

        /*if($permissionStored) {
            event(new PermissionSeederEvent());
        }*/

        return redirect()->route('userpermission.index')->with('success','Permission Added Successfully.');
    }

    public function edit($id)
    {
    	$permission = New_Permissions::find($id);

       	$modules = Module::getModules();
    	$selected_module = $permission->module_id;

        $action = 'edit';

        return view('adminlte::new_permissions.edit',compact('action','permission','modules','selected_module'));
    }

    public function update(Request $request, $id)
    {
    	$permission = New_Permissions::find($id);
    	$permission->module_id = $request->input('module_id');
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permissionStored = $permission->save();

        /*if($permissionStored) {
            event(new PermissionSeederEvent());
        }*/

        return redirect()->route('userpermission.index')->with('success','Permission Updated Successfully.');
    }

    public function destroy($id)
    {
    	New_Permissions::where('id',$id)->delete();

    	return redirect()->route('userpermission.index')->with('success','Permission Deleted Successfully.');
    }
}
