<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module;
use App\ModuleVisibleUser;
use App\User;

class ModuleVisibleController extends Controller
{
    public function index() {

    	$module_user = ModuleVisibleUser::getAllModuleVisibleUser();

    	return view('adminlte::modulevisible.index',compact('module_user'));
    }

    public function create() {

        $users = User::getAllUsers();
        $modules = Module::getAllModulesName();
        $selected_modules = array();
        $userid = 0;
        $action = 'add';

        return view('adminlte::modulevisible.create',compact('action','users','modules','selected_modules','userid'));
    }

    public function store(Request $request) {

        $user_id = $request->get('user_id');
        $module_ids = $request->get('module_ids');

        foreach ($module_ids as $key => $value) {
            $module_user_add = new ModuleVisibleUser();
            $module_user_add->user_id = $user_id;
            $module_user_add->module_id = $value;
            $module_user_add->save();
        }

        return redirect()->route('modulevisible.index')->with('success','Module Visibility Added Successfully.');
    }

    public function edit($id) {

        $users = User::getAllUsers();
        $modules = Module::getAllModulesName();

        $module_user = ModuleVisibleUser::find($id);
        $userid = $module_user->user_id;

        $module_userwise = ModuleVisibleUser::where('user_id',$userid)->get();
        foreach ($module_userwise as $key => $value) {
            $selected_modules[] = $value->module_id;
        }
        $action = 'edit';

        return view('adminlte::modulevisible.edit',compact('action','users','modules','selected_modules','userid','module_user'));
    }

    public function update(Request $request,$id) {

        $user_id = $request->get('user_id');
        $module_ids = $request->get('module_ids');

        $module_user_delete = ModuleVisibleUser::where('user_id',$user_id)->delete();

        foreach ($module_ids as $key => $value) {
            $module_user_add = new ModuleVisibleUser();
            $module_user_add->user_id = $user_id;
            $module_user_add->module_id = $value;
            $module_user_add->save();
        }

        return redirect()->route('modulevisible.index')->with('success','Module Visibility Updated Successfully.');
    }

    public function destroy($user_id) {

        ModuleVisibleUser::where('user_id',$user_id)->delete();

        return redirect()->route('modulevisible.index')->with('success','Module Visibility Deleted Successfully.');
    }

    public function userWiseModuleAjax(){

        $user_id = $_POST['user_id'];

        $module_user = ModuleVisibleUser::getModuleByUserIdAjax($user_id);
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
        //print_r($msg);exit;
        return json_encode($msg);exit;
    }
}
