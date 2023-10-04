<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\UserBenchMark;
use App\User;

class UserBenchMarkController extends Controller
{
    public function index() {

        $active_user_bench_mark = UserBenchMark::getAllUsersBenchMark('active');
        $active_count = sizeof($active_user_bench_mark);

        $inactive_user_bench_mark = UserBenchMark::getAllUsersBenchMark('inactive');
        $inactive_count = sizeof($inactive_user_bench_mark);

        $total_count = $active_count + $inactive_count;

    	return view('adminlte::userbenchmark.index',compact('active_user_bench_mark', 'inactive_user_bench_mark', 'active_count', 'inactive_count', 'total_count'));
    }

    public function create() {

    	$action = 'add';

    	$recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $hr_user_id = getenv('HRUSERID');

        $type_array = array($recruitment,$hr_advisory);
        $all_users = User::getAllUsers($type_array);

        $get_hr_user_name = User::getUserNameById($hr_user_id);
        $all_users[$hr_user_id] = $get_hr_user_name;

    	$select_user_id = '';

    	return view('adminlte::userbenchmark.create',compact('action','all_users','select_user_id'));
    }

    public function store(Request $request) {

    	$user_id = $request->get('user_id');
    	$no_of_resumes = $request->get('no_of_resumes');
    	$shortlist_ratio = $request->get('shortlist_ratio');
    	$interview_ratio = $request->get('interview_ratio');
    	$selection_ratio = $request->get('selection_ratio');
    	$offer_acceptance_ratio = $request->get('offer_acceptance_ratio');
    	$joining_ratio = $request->get('joining_ratio');
    	$after_joining_success_ratio = $request->get('after_joining_success_ratio');
    	
    	$user_bench_mark = new UserBenchMark();
    	$user_bench_mark->user_id = $user_id;
    	$user_bench_mark->no_of_resumes = $no_of_resumes;
    	$user_bench_mark->shortlist_ratio = $shortlist_ratio;
    	$user_bench_mark->interview_ratio = $interview_ratio;
    	$user_bench_mark->selection_ratio = $selection_ratio;
    	$user_bench_mark->offer_acceptance_ratio = $offer_acceptance_ratio;
    	$user_bench_mark->joining_ratio = $joining_ratio;
    	$user_bench_mark->after_joining_success_ratio = $after_joining_success_ratio;
    	$user_bench_mark->save();

    	return redirect()->route('userbenchmark.index')->with('success','Benchmark Added Successfully.');
    }

    public function edit($id) {

    	$action = 'edit';
		$id = Crypt::decrypt($id);


        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $hr_user_id = getenv('HRUSERID');

        $type_array = array($recruitment,$hr_advisory);
        $all_users = User::getAllUsers($type_array);

        $get_hr_user_name = User::getUserNameById($hr_user_id);
        $all_users[$hr_user_id] = $get_hr_user_name;

        $user_bench_mark = UserBenchMark::find($id);
    	$select_user_id = $user_bench_mark->user_id;

    	return view('adminlte::userbenchmark.edit',compact('user_bench_mark','action','all_users','select_user_id'));
    }

    public function update($id,Request $request) {
    	
    	$user_id = $request->get('user_id');
    	$no_of_resumes = $request->get('no_of_resumes');
    	$shortlist_ratio = $request->get('shortlist_ratio');
    	$interview_ratio = $request->get('interview_ratio');
    	$selection_ratio = $request->get('selection_ratio');
    	$offer_acceptance_ratio = $request->get('offer_acceptance_ratio');
    	$joining_ratio = $request->get('joining_ratio');
    	$after_joining_success_ratio = $request->get('after_joining_success_ratio');
    	
    	$user_bench_mark = UserBenchMark::find($id);
    	$user_bench_mark->user_id = $user_id;
    	$user_bench_mark->no_of_resumes = $no_of_resumes;
    	$user_bench_mark->shortlist_ratio = $shortlist_ratio;
    	$user_bench_mark->interview_ratio = $interview_ratio;
    	$user_bench_mark->selection_ratio = $selection_ratio;
    	$user_bench_mark->offer_acceptance_ratio = $offer_acceptance_ratio;
    	$user_bench_mark->joining_ratio = $joining_ratio;
    	$user_bench_mark->after_joining_success_ratio = $after_joining_success_ratio;
    	$user_bench_mark->save();

    	return redirect()->route('userbenchmark.index')->with('success','Benchmark Updated Successfully.');
    }

    public function destroy($id) {

    	UserBenchMark::where('id',$id)->delete();
    	return redirect()->route('userbenchmark.index')->with('success','Benchmark Deleted Successfully.');
    }
}