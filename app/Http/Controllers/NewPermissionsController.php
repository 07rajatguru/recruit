<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permission;
use App\Module;

class NewPermissionsController extends Controller
{
    public function index() {

    	$permissions = Permission::getAllPermissionsDetails();
    	$count = sizeof($permissions);

    	return view('adminlte::new_permissions.index',compact('permissions','count'));
    }

    public function create() {

    	$action = 'add';
    	$modules = Module::getModules();
    	$selected_module = '';

        return view('adminlte::new_permissions.create', compact('action','modules','selected_module'));
    }

    public function store(Request $request) {

    	$permission = new Permission();
    	$permission->module_id = $request->input('module_id');
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permissionStored = $permission->save();

        return redirect()->route('userpermission.index')->with('success','Permission Created Successfully.');
    }

    public function edit($id) {

    	$permission = Permission::find($id);

       	$modules = Module::getModules();
    	$selected_module = $permission->module_id;

        $action = 'edit';

        return view('adminlte::new_permissions.edit',compact('action','permission','modules','selected_module'));
    }

    public function update(Request $request, $id) {

    	$permission = Permission::find($id);
    	$permission->module_id = $request->input('module_id');
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permissionStored = $permission->save();

        return redirect()->route('userpermission.index')->with('success','Permission Updated Successfully.');
    }

    public function destroy($id) {

    	Permission::where('id',$id)->delete();
    	return redirect()->route('userpermission.index')->with('success','Permission Deleted Successfully.');
    }
}