<?php

namespace App\Http\Controllers;

use App\Events\PermissionSeederEvent;
use App\Permission;
use Illuminate\Http\Request;
use App\User;
class PermissionsController extends Controller
{
    //

    public function index(Request $request) {

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $permissions = Permission::orderBy('id','desc')->get();
        return view('adminlte::permissions.index',compact('permissions','isSuperAdmin'));
        //return view('adminlte::permissions.index',compact('permissions'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(Request $request) {
        return view('adminlte::permissions.create', array('action' => 'add'));
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $permission = new Permission();
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permissionStored = $permission->save();

        if($permissionStored) {
            event(new PermissionSeederEvent());
        }

        return redirect()->route('permission.index')->with('success','Permission Created Successfully');

    }

    public function show($id) {

    }

    public function edit($id) {
        $permission = Permission::find($id);

        $viewVariable = array();
        $viewVariable['permission'] = $permission;
        $viewVariable['action'] = 'edit';

        return view('adminlte::permissions.edit',$viewVariable);
    }

    public function update(Request $request, $id) {

        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $permission = Permission::find($id);
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permissionStored = $permission->save();

        if($permissionStored) {
            event(new PermissionSeederEvent());
        }

        return redirect()->route('permission.index')->with('success','Permission Updated Successfully');

    }

    public function destroy($id) {
        $permissionDelete = Permission::where('id',$id)->delete();

        return redirect()->route('permission.index')->with('success','Permission deleted Successfully');
    }


}
