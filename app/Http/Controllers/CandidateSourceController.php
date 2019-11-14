<?php

namespace App\Http\Controllers;

use App\CandidateSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\USer;

class CandidateSourceController extends Controller
{
    //

    public function index(Request $request) {

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $candidateSource = CandidateSource::orderBy('id','desc')->get();
        return view('adminlte::candidateSource.index',compact('candidateSource','isSuperAdmin'));

        //$candidateSource = CandidateSource::orderBy('id','desc')->paginate(10);

        //return view('adminlte::candidateSource.index',compact('candidateSource'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(Request $request) {
        return view('adminlte::candidateSource.create', array('action' => 'add'));
    }

    public function store(Request $request) {

        $candidateSource = new CandidateSource();
        $candidateSource->name = $request->input('name');

        $validator = \Validator::make(Input::all(),$candidateSource::$rules);

        if($validator->fails()){
            return redirect('candidateSource/create')->withInput(Input::all())->withErrors($validator->errors());
        }
        $candidateSourceStored = $candidateSource->save();

        return redirect()->route('candidateSource.index')->with('success','Candidate Source Created Successfully');
    }

    public function edit($id){
        $candidateSource = CandidateSource::find($id);

        $viewVariable = array();
        $viewVariable['candidateSource'] = $candidateSource;
        $viewVariable['action'] = 'edit';

        return view('adminlte::candidateSource.edit',$viewVariable);
    }

    public function update(Request $request, $id){

        $candidateSource = CandidateSource::find($id);
        $candidateSource->name = $request->input('name');
        $validator = \Validator::make(Input::all(),$candidateSource::$rules);

        if($validator->fails()){
            return redirect('candidateSource/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $candidateSourceStored = $candidateSource->save();

        return redirect()->route('candidateSource.index')->with('success','Candidate Source Updated Successfully');
    }

    public function destroy($id){
        $candidateSourceDelete = CandidateSource::where('id',$id)->delete();

        return redirect()->route('candidateSource.index')->with('success','Candidate Source deleted Successfully');
    }
}
