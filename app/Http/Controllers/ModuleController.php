<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module;
use App\ModuleVisibleUser;
use App\User;

class ModuleController extends Controller
{
    public function index() {

    	$modules = Module::getAllModules();
    	return view('adminlte::module.index',compact('modules'));
    }

    public function create() {

    	$action = 'add';
    	return view('adminlte::module.create',compact('action'));
    }

    public function store(Request $request) {

    	$this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

    	$name = $request->get('name');
    	$description = $request->get('description');
        $status = $request->get('status');

    	$module = new Module();
    	$module->name = $name;
    	$module->description = $description;
        $module->status = $status;
    	$module->save();

    	return redirect()->route('module.index')->with('success','Module Added Successfully.');
    }

    public function edit($id) {

    	$module = Module::find($id);
    	$action = 'edit';
    	return view('adminlte::module.edit',compact('module','action'));
    }

    public function update(Request $request,$id) {

    	$this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

    	$name = $request->get('name');
    	$description = $request->get('description');
        $status = $request->get('status');

    	$module = Module::find($id);
    	$module->name = $name;
    	$module->description = $description;
        $module->status = $status;
    	$module->save();

    	return redirect()->route('module.index')->with('success','Module Updated Successfully.');
    }

    public function destroy($id) {

    	Module::where('id',$id)->delete();
    	return redirect()->route('module.index')->with('success','Module Deleted Successfully.');
    }
}