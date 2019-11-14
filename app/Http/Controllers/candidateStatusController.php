<?php

namespace App\Http\Controllers;

use App\CandidateStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\USer;

class candidateStatusController extends Controller
{
    //
    public function index(Request $request){
        /*$candidateStatus = CandidateStatus::orderBy('id','desc')->paginate(15);
        return view('adminlte::candidateStatus.index',compact('candidateStatus'))->with('i', ($request->input('page', 1) - 1) * 15);*/

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $candidateStatus = CandidateStatus::orderBy('id','desc')->get();
        return view('adminlte::candidateStatus.index',compact('candidateStatus','isSuperAdmin'));
    }

    public function create(Request $request){

        return view('adminlte::candidateStatus.create', array('action' => 'add'));

    }

    public function store(Request $request){

        $candidateStatus = new CandidateStatus();
        $candidateStatus->name = $request->input('name');

        $validator = \Validator::make(Input::all(),$candidateStatus::$rules);

        if($validator->fails()){
            return redirect('candidateSource/create')->withInput(Input::all())->withErrors($validator->errors());
        }
        $candidateStatusStored = $candidateStatus->save();

        return redirect()->route('candidateStatus.index')->with('success','Candidate Status Created Successfully');
    }

    public function edit(Request $request,$id){
        $candidateStatus = CandidateStatus::find($id);

        $viewVariable = array();
        $viewVariable['candidateStatus'] = $candidateStatus;
        $viewVariable['action'] = 'edit';

        return view('adminlte::candidateStatus.edit',$viewVariable);
    }

    public function update(Request $request,$id){

        $candidateStatus = CandidateStatus::find($id);
        $candidateStatus->name = $request->input('name');
        $validator = \Validator::make(Input::all(),$candidateStatus::$rules);

        if($validator->fails()){
            return redirect('candidateStatus/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }
        $candidateStatusStored = $candidateStatus->save();

        return redirect()->route('candidateStatus.index')->with('success','Candidate Status Updated Successfully');
    }

    public function destroy($id){
        $candidateStatusDelete = CandidateStatus::where('id',$id)->delete();

        return redirect()->route('candidateStatus.index')->with('success','Candidate Status deleted Successfully');
    }
}
