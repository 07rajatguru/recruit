<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use DB;

class DepartmentController extends Controller
{
    public function index(Request $request) {

        $departments = Department::orderBy('id','ASC')->get();

        $count = sizeof($departments);
        return view('adminlte::departments.index',compact('departments','count'));
    }

    public function create() {

        $action = 'add';
        return view('adminlte::departments.create',compact('action'));
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required'
        ]);

        $departments = new Department();
        $departments->name = $request->input('name');
        $departments->save();

        return redirect()->route('departments.index')->with('success','Department Addedd Successfully.');
    }

    public function edit($id) {

        $departments = Department::find($id);
        $action = 'edit';

        return view('adminlte::departments.edit',compact('departments','action'));
    }

    public function update(Request $request, $id) {

        $this->validate($request, [
            'name' => 'required'
        ]);

        $departments = Department::find($id);
        $departments->name = $request->input('name');
        $departments->save();

        return redirect()->route('departments.index')->with('success','Department Updated Successfully.');
    }

    public function destroy($id) {

        DB::table("department")->where('id',$id)->delete();
        return redirect()->route('departments.index')->with('success','Department Deleted Successfully.');
    }
}