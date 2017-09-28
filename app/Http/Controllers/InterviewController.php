<?php

namespace App\Http\Controllers;

use App\CandidateBasicInfo;
use App\ClientBasicinfo;
use App\Date;
use App\Interview;
use App\JobOpen;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class InterviewController extends Controller
{
    //

    public function index(){

        $interViews = Interview::join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')
            ->join('client_basicinfo','client_basicinfo.id','=','interview.client_id')
            ->Leftjoin('users','users.id','=','interview.interviewer_id')
            ->select('interview.*', DB::raw('CONCAT(candidate_basicinfo.fname, " ", candidate_basicinfo.lname) AS candidate_name'),
                'client_basicinfo.name as client_name','users.name as interviewer_name')
            ->get();

        return view('adminlte::interview.index', array('interViews' => $interViews));
    }

    public function create(){

        $viewVariable = array();
        $viewVariable['candidate'] = CandidateBasicInfo::getCandidateArray();
        $viewVariable['client'] = ClientBasicinfo::getClientArray();
        $viewVariable['postingArray'] = JobOpen::getPostingTitleArray();
        //$viewVariable['interviewer'] = User::getInterviewerArray();
        $viewVariable['type'] = Interview::getTypeArray();
        $viewVariable['status'] = Interview::getInterviewStatus();
        $viewVariable['users'] = User::getAllUsers();
        $viewVariable['action'] = 'add';

        return view('adminlte::interview.create', $viewVariable);
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        $data = array();
        $data['interview_name'] = $request->get('interview_name');
        $data['candidate_id'] = $request->get('candidate_id');
        $data['interviewer_id'] = $request->get('interviewer_id');
        $data['client'] = $request->get('client_id');
        $data['interview_date'] = $dateClass->changeDMYHMStoYMDHMS($request->get('interview_date'));
        $data['location'] = $request->get('location');
        $data['comments'] = $request->get('comments');
        $data['posting_title'] = $request->get('posting_title');
        $data['type'] = $request->get('type');
        $data['status'] = $request->get('status');
        $data['interview_owner_id'] = $user_id;

        $interview = Interview::createInterview($data);

        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewStored = $interview->save();

        return redirect()->route('interview.index')->with('success','Interview Created Successfully');

    }

    public function edit($id){

        $dateClass = new Date();
        $interview = Interview::find($id);

        $viewVariable = array();
        $viewVariable['candidate'] = CandidateBasicInfo::getCandidateArray();
        $viewVariable['client'] = ClientBasicinfo::getClientArray();
        $viewVariable['postingArray'] = JobOpen::getPostingTitleArray();
        $viewVariable['interviewer'] = User::getInterviewerArray();
        $viewVariable['type'] = Interview::getTypeArray();
        $viewVariable['status'] = Interview::getInterviewStatus();
        $viewVariable['users'] = User::getAllUsers();
        $viewVariable['interview'] = $interview;
        $viewVariable['action'] = 'edit';
        $viewVariable['fromDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->from);
        $viewVariable['toDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->to);

        return view('adminlte::interview.edit', $viewVariable);

    }

    public function update(Request $request, $id){
        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        $interview_name = $request->get('interview_name');
        $candidate_id = $request->get('candidate_id');
        $interviewer = $request->get('interviewer_id');
        $client = $request->get('client_id');
        $from = $dateClass->changeDMYtoYMD($request->get('from'));
        $to = $dateClass->changeDMYtoYMD($request->get('to'));
        $location = $request->get('location');
        $comments = $request->get('comments');
        $posting_title = $request->get('posting_title');
        $type = $request->get('type');
        $status = $request->get('status');
        $interview_owner_id = $user_id;

        $interview = Interview::find($id);
        if(isset($interview_name))
            $interview->interview_name = $interview_name;
        if(isset($candidate_id))
            $interview->candidate_id = $candidate_id;
        if(isset($client))
            $interview->client_id = $client;
        if(isset($posting_title))
            $interview->posting_title = $posting_title;
        if(isset($interviewer))
            $interview->interviewer_id = $interviewer;
        if(isset($type))
            $interview->type = $type;
        if(isset($from))
            $interview->from = $from;
        if(isset($to))
            $interview->to = $to;
        if(isset($location))
            $interview->location = $location;
        if(isset($status))
            $interview->status = $status;
        if(isset($comments))
            $interview->comments = $comments;
        $interview->interview_owner_id = $interview_owner_id;


        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewUpdated = $interview->save();

        return redirect()->route('interview.index')->with('success','Interview Updated Successfully');
    }

    public function show($id){

        $dateClass = new Date();

        $interviewDetails = Interview::join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')
            ->leftjoin('client_basicinfo','client_basicinfo.id','=','interview.client_id')
            ->leftjoin('job_openings','job_openings.id','=','interview.posting_title')
            ->leftjoin('users','users.id','=','interview.interviewer_id')
            ->select('interview.*', DB::raw('CONCAT(candidate_basicinfo.fname, " ", candidate_basicinfo.lname) AS candidate_name'),
                'client_basicinfo.name as client_name', 'job_openings.posting_title as posting_title','users.name as interviewer_name')
            ->where('interview.id','=',$id)
            ->first();

        $interviewOwnerId = $interviewDetails->interview_owner_id;

        if(isset($interviewOwnerId)){
            $interviewOwnerDetails = User::find($interviewDetails->interview_owner_id);
            $interviewOwner = $interviewOwnerDetails->name;
        } else {
            $interviewOwner = null;
        }
        
        $interview = array();
        $interview['id'] = $id;
        $interview['interview_name'] = $interviewDetails->interview_name;
        $interview['candidate'] = $interviewDetails->candidate_name;
        $interview['client'] = $interviewDetails->client_name;
        $interview['posting_title'] = $interviewDetails->posting_title;
        $interview['interviewer'] = $interviewDetails->interviewer_name;
        $interview['type'] = $interviewDetails->type;
        $interview['interview_date'] = $dateClass->changeYMDtoDMY($interviewDetails->interview_date);
        $interview['location'] = $interviewDetails->location;
        $interview['status'] = $interviewDetails->status;
        $interview['comments'] = $interviewDetails->comments;
        $interview['interviewOwner'] = $interviewOwner;

        return view('adminlte::interview.show', $interview);
    }

    public function destroy($id){

        $interviewDelete = Interview::where('id',$id)->delete();

        return redirect()->route('interview.index')->with('success','Interview Deleted Successfully');

    }
}
