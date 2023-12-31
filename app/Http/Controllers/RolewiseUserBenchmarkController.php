<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\RolewiseUserBenchmark;
use App\Role;
use App\RoleUser;
use DB;

class RolewiseUserBenchmarkController extends Controller
{
    public function index() {

        $bench_mark = RolewiseUserBenchmark::getRolewiseBenchMarkList();
        $count = sizeof($bench_mark);

        return view('adminlte::rolewisebenchmark.index',compact('bench_mark','count'));
    }

    public function create() {

        $action = 'add';

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $operations = getenv('OPERATIONS');
        $management = getenv('MANAGEMENT');

        $type_array = array($recruitment,$hr_advisory,$operations,$management);
        $all_roles = Role::getRolesByDepartment($type_array);

        $select_role_id = '';

        return view('adminlte::rolewisebenchmark.create',compact('action','all_roles','select_role_id'));
    }

    public function store(Request $request) {

        $role_id = $request->get('role_id');
        $no_of_resumes = $request->get('no_of_resumes');
        $shortlist_ratio = $request->get('shortlist_ratio');
        $interview_ratio = $request->get('interview_ratio');
        $selection_ratio = $request->get('selection_ratio');
        $offer_acceptance_ratio = $request->get('offer_acceptance_ratio');
        $joining_ratio = $request->get('joining_ratio');
        $after_joining_success_ratio = $request->get('after_joining_success_ratio');
        $applicable_date = $request->get('applicable_date');
        $end_date = date("Y-m-d", strtotime("-1 day ".$applicable_date));
        
        $bench_mark = new RolewiseUserBenchmark();
        $bench_mark->role_id = $role_id;
        $bench_mark->no_of_resumes = $no_of_resumes;
        $bench_mark->shortlist_ratio = $shortlist_ratio;
        $bench_mark->interview_ratio = $interview_ratio;
        $bench_mark->selection_ratio = $selection_ratio;
        $bench_mark->offer_acceptance_ratio = $offer_acceptance_ratio;
        $bench_mark->joining_ratio = $joining_ratio;
        $bench_mark->after_joining_success_ratio = $after_joining_success_ratio;
        $bench_mark->applicable_date = $applicable_date;

        $role_bench_mark = RolewiseUserBenchmark::getBenchMarkByRoleID($role_id);
        if (isset($role_bench_mark) && sizeof($role_bench_mark) > 0) {
            $role_bench_mark_id = $role_bench_mark['id'];

            $rbm_update = RolewiseUserBenchmark::find($role_bench_mark_id);
            $rbm_update->end_date = $end_date;
            $rbm_update->save();
        }

        $bench_mark->save();

        return redirect()->route('rolewisebenchmark.index')->with('success','Benchmark Added Successfully.');
    }

    public function edit($id) {

        $id = \Crypt::decrypt($id);

        $action = 'edit';

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $operations = getenv('OPERATIONS');
        $management = getenv('MANAGEMENT');

        $type_array = array($recruitment,$hr_advisory,$operations,$management);
        $all_roles = Role::getRolesByDepartment($type_array);

        $bench_mark = RolewiseUserBenchmark::find($id);
        $select_role_id = $bench_mark->role_id;

        return view('adminlte::rolewisebenchmark.edit',compact('bench_mark','action','all_roles','select_role_id'));
    }

    public function update($id,Request $request) {
        
        $role_id = $request->get('role_id');
        $no_of_resumes = $request->get('no_of_resumes');
        $shortlist_ratio = $request->get('shortlist_ratio');
        $interview_ratio = $request->get('interview_ratio');
        $selection_ratio = $request->get('selection_ratio');
        $offer_acceptance_ratio = $request->get('offer_acceptance_ratio');
        $joining_ratio = $request->get('joining_ratio');
        $after_joining_success_ratio = $request->get('after_joining_success_ratio');
        $applicable_date = $request->get('applicable_date');
        $end_date = date("Y-m-d", strtotime("-1 day ".$applicable_date));
        
        $bench_mark = RolewiseUserBenchmark::find($id);
        $bench_mark->role_id = $role_id;
        $bench_mark->no_of_resumes = $no_of_resumes;
        $bench_mark->shortlist_ratio = $shortlist_ratio;
        $bench_mark->interview_ratio = $interview_ratio;
        $bench_mark->selection_ratio = $selection_ratio;
        $bench_mark->offer_acceptance_ratio = $offer_acceptance_ratio;
        $bench_mark->joining_ratio = $joining_ratio;
        $bench_mark->after_joining_success_ratio = $after_joining_success_ratio;
        $bench_mark->applicable_date = $applicable_date;

        $role_bench_mark = RolewiseUserBenchmark::getBenchMarkByRoleID($role_id);
        if (isset($role_bench_mark) && sizeof($role_bench_mark) > 0) {
            $role_bench_mark_id = $role_bench_mark['id'];

            $rbm_update = RolewiseUserBenchmark::find($role_bench_mark_id);
            $rbm_update->end_date = $end_date;
            $rbm_update->save();
        }

        $bench_mark->save();

        // Update in user benchmark table
        $user_ids_array = RoleUser::getUserIdsByRoleId($role_id);

        if(isset($user_ids_array) && sizeof($user_ids_array) > 0) {

            foreach ($user_ids_array as $key => $value) {

                DB::statement("UPDATE `user_bench_mark` SET `no_of_resumes` = '$no_of_resumes',`shortlist_ratio` = '$shortlist_ratio',`interview_ratio` = '$interview_ratio',`selection_ratio` = '$selection_ratio',`offer_acceptance_ratio` = '$offer_acceptance_ratio',`joining_ratio` = '$joining_ratio',`after_joining_success_ratio` = '$after_joining_success_ratio' WHERE `user_id` = '$value'");
            }
        }
        return redirect()->route('rolewisebenchmark.index')->with('success','Benchmark Updated Successfully.');
    }

    public function destroy($id) {

        RolewiseUserBenchmark::where('id',$id)->delete();
        return redirect()->route('rolewisebenchmark.index')->with('success','Benchmark Deleted Successfully.');
    }
}