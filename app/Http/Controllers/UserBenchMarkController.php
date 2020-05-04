<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\UserBenchMark;
use App\User;

class UserBenchMarkController extends Controller
{
    public function index() {

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

    	$user_bench_mark = UserBenchMark::getAllUsersBenchMarK();
    	$count = sizeof($user_bench_mark);

    	return view('adminlte::userbenchmark.index',compact('user_bench_mark','count','isSuperAdmin'));
    }

    public function create(){

    	$action = 'add';
    	$all_users = User::getAllUsers('recruiter');
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

    	return redirect()->route('userbenchmark.index')->with('success','Bench Mark Added Successfully.');
    }

    public function edit($id) {

    	$user_bench_mark = UserBenchMark::find($id);
    	$action = 'edit';

    	$all_users = User::getAllUsers('recruiter');
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

    	return redirect()->route('userbenchmark.index')->with('success','Bench Mark Updated Successfully.');
    }

    public function destroy($id) {

    	UserBenchMark::where('id',$id)->delete();

    	return redirect()->route('userbenchmark.index')->with('success','Bench Mark Deleted Successfully.');
    }
}
