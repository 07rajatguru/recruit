<?php

namespace App\Http\Controllers;

use App\DailyReport;
use App\Date;
use App\EmailsNotifications;
use App\EmailsTraking;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\ClientBasicinfo;
use App\CandidateStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class DailyReportController extends Controller
{

    public function setsession($key,$val){
        if(Session::has($key) && $val != null){
            return Session::get($key);
        } else {
            Session::put($key,$val);
            return Session::get($key);
        }
    }

    public function getsession($key){
        if(Session::has($key)){
            return Session::get($key);
        } else {
            return null;
        }
    }

    public function removesession($key){
        if(Session::has($key)){
            Session::forget($key);
        }
    }

    public function index(Request $request){

        $dateClass = new Date();

        $fromDate = Input::get('fromDate');
        $toDate = Input::get('toDate');

        if(!isset($fromDate)){
            $fromDate = Session::get('fromDate');

            if(!isset($fromDate)){
                $fromDate = date('Y-m-01');
            }
            $removeFromDateSession = $this->removesession('fromDate');
        }

        if(!isset($toDate)){
            $toDate = Session::get('toDate');

            if(!isset($toDate)){
                $toDate = date('Y-m-31');
            }
            $removeToDateSession = $this->removesession('toDate');
        }

//        $fromDate = date('Y-m-01');
//        $toDate = date('Y-m-31');

        $dailyReport = DailyReport::leftjoin('users','users.id','=','daily_report.uid')
            ->leftjoin('client_basicinfo','client_basicinfo.id','=','daily_report.client_id')
            ->leftjoin('candidate_status','candidate_status.id','=','daily_report.candidate_status_id')
            ->select('daily_report.id as id', 'daily_report.position_name as position_name', 'daily_report.location as location',
                'daily_report.cvs_to_tl as cvs_to_tl', 'daily_report.cvs_to_client as cvs_to_client',
                DB::raw('DATE_FORMAT(daily_report.report_date, "%d-%m-%Y") as report_date'),
                'users.name as user_name', 'client_basicinfo.name as client_name',
                'candidate_status.name as candidate_status')
            ->whereBetween('daily_report.report_date', array($fromDate, $toDate))
            ->orderBy('daily_report.report_date','asc')
            ->get();

        $viewVariable = array();
        $viewVariable['dailyReports'] = $dailyReport;
        $viewVariable['fromDate'] = $dateClass->changeYMDtoDMY($fromDate);
        $viewVariable['toDate'] = $dateClass->changeYMDtoDMY($toDate);

//        print_r($toDate);exit;

        return view('adminlte::dailyreport.index', $viewVariable);
    }

    public function create()
    {
        $client_res = ClientBasicinfo::orderBy('id','DESC')->get();
        $client = array();

        if(sizeof($client_res)>0){
            foreach($client_res as $r){
                $client[$r->id]=$r->name;
            }
        }

        $status_res = CandidateStatus::orderBy('id','DESC')->get();
        $status = array();

        if(sizeof($status_res)>0){
            foreach($status_res as $r){
                $status[$r->id]=$r->name;
            }
        }

        $action = "add" ;
        return view('adminlte::dailyreport.create',compact('action','client','status'));
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;

        $dateClass = new Date();

        $position_name = $request->position_name;
        $client_id = $request->client_id;
        $location = $request->location;
        $cvs_to_tl = $request->cvs_to_tl;
        $cvs_to_client = $request->cvs_to_client;
        $candidate_status_id = $request->candidate_status_id;
        $report_date = $request->report_date;

        $formattedReportDate = $dateClass->changeDMYtoYMD($report_date);

        $dailyReport = new DailyReport();
        $dailyReport->uid = $user_id;
        $dailyReport->position_name = $position_name;
        $dailyReport->client_id = $client_id;
        $dailyReport->location = $location;
        $dailyReport->cvs_to_tl = $cvs_to_tl;
        $dailyReport->cvs_to_client = $cvs_to_client;
        $dailyReport->candidate_status_id = $candidate_status_id;
        $dailyReport->report_date = $formattedReportDate;


        $validator = \Validator::make(Input::all(),$dailyReport::$rules);

        if($validator->fails()){
            return redirect('dailyreport/create')->withInput(Input::all())->withErrors($validator->errors());
        }
        $dailyReportStored = $dailyReport->save();

        return redirect()->route('dailyreport.index')->with('success','Report Created Successfully');
    }

    public function edit($id){

        $client_res = ClientBasicinfo::orderBy('id','DESC')->get();
        $client = array();

        if(sizeof($client_res)>0){
            foreach($client_res as $r){
                $client[$r->id]=$r->name;
            }
        }
        $status_res = CandidateStatus::orderBy('id','DESC')->get();
        $status = array();

        if(sizeof($status_res)>0){
            foreach($status_res as $r){
                $status[$r->id]=$r->name;
            }
        }
        $action = "edit" ;

        $dailyReport = DailyReport::leftjoin('users','users.id','=','daily_report.uid')
            ->leftjoin('client_basicinfo','client_basicinfo.id','=','daily_report.client_id')
            ->leftjoin('candidate_status','candidate_status.id','=','daily_report.candidate_status_id')
            ->select('daily_report.id as id', 'daily_report.position_name as position_name', 'daily_report.location as location',
                'daily_report.cvs_to_tl as cvs_to_tl', 'daily_report.cvs_to_client as cvs_to_client',
                DB::raw('DATE_FORMAT(daily_report.report_date, "%d-%m-%Y") as report_date') , 'users.id as user_id',  'users.name as user_name',
                'client_basicinfo.id as client_id','client_basicinfo.name as client_name',
                'candidate_status.id as candidate_status_id', 'candidate_status.name as candidate_status')
            ->where('daily_report.id',$id)
            ->first();

        $viewVariable = array();
        $viewVariable['client'] = $client;
        $viewVariable['status'] = $status;
        $viewVariable['dailyReport'] = $dailyReport;
        $viewVariable['action'] = $action;

        return view('adminlte::dailyreport.edit',$viewVariable);
    }

    public function update(Request $request, $id){

        $user_id = \Auth::user()->id;

        $dateClass = new Date();

        $position_name = $request->position_name;
        $client_id = $request->client_id;
        $location = $request->location;
        $cvs_to_tl = $request->cvs_to_tl;
        $cvs_to_client = $request->cvs_to_client;
        $candidate_status_id = $request->candidate_status_id;
        $report_date = $request->report_date;
        $formattedReportDate = $dateClass->changeDMYtoYMD($report_date);

        $dailyReport = DailyReport::find($id);
        $dailyReport->uid = $user_id;
        $dailyReport->position_name = $position_name;
        $dailyReport->client_id = $client_id;
        $dailyReport->location = $location;
        $dailyReport->cvs_to_tl = $cvs_to_tl;
        $dailyReport->cvs_to_client = $cvs_to_client;
        $dailyReport->candidate_status_id = $candidate_status_id;
        $dailyReport->report_date = $formattedReportDate;

        $validator = \Validator::make(Input::all(),$dailyReport::$rules);

        if($validator->fails()){
            return redirect('dailyreport/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }
        $dailyReportStored = $dailyReport->save();

        return redirect()->route('dailyreport.index')->with('success','Report Updated Successfully');

    }

    public function show($id){

        $dailyReport = DailyReport::leftjoin('users','users.id','=','daily_report.uid')
            ->leftjoin('client_basicinfo','client_basicinfo.id','=','daily_report.client_id')
            ->leftjoin('candidate_status','candidate_status.id','=','daily_report.candidate_status_id')
            ->select('daily_report.id as id', 'daily_report.position_name as position_name', 'daily_report.location as location',
                'daily_report.cvs_to_tl as cvs_to_tl', 'daily_report.cvs_to_client as cvs_to_client',
                DB::raw('DATE_FORMAT(daily_report.report_date, "%d-%m-%Y") as report_date'), 'users.name as user_name',
                'client_basicinfo.name as client_name',
                'candidate_status.name as candidate_status')
            ->where('daily_report.id',$id)
            ->first();

        $viewVariable = array();

        $viewVariable['id'] = $id;
        $viewVariable['position_name'] = $dailyReport->position_name;
        $viewVariable['report_date'] = $dailyReport->report_date;
        $viewVariable['client_name'] = $dailyReport->client_name;
        $viewVariable['cvs_to_tl'] = $dailyReport->cvs_to_tl;
        $viewVariable['candidate_status'] = $dailyReport->candidate_status;
        $viewVariable['location'] = $dailyReport->location;
        $viewVariable['cvs_to_client'] = $dailyReport->cvs_to_client;

        return view('adminlte::dailyreport.show',$viewVariable);
    }

    public function destroy($id){

        $daily_report_delete = DailyReport::where('id',$id)->delete();

        return redirect()->route('dailyreport.index')->with('success','Report Deleted Successfully');
    }

    public function reportMailToandCC(){
        $dateClass = new Date();

        $fromDate = Input::get('fromDate');
        $toDate = Input::get('toDate');

        $users = User::getAllUsers();

        $viewVariable = array();
        $viewVariable['fromDate'] = $fromDate;
        $viewVariable['toDate'] = $toDate;
        $viewVariable['users'] = $users;

        return view('adminlte::dailyreport.reportMail', $viewVariable);
    }

    public function reportMail(){

        $dateClass = new Date();

        $user_id = \Auth::user()->id;

        $fromDate = Input::get('fromDate');
        $toDate = Input::get('toDate');
        $toUser = Input::get('to');
        $ccUser = Input::get('cc');
        $bccUser = Input::get('bcc');

        $currentDate = date('Y-m-d');

        $dailyReport = DailyReport::leftjoin('users','users.id','=','daily_report.uid')
            ->leftjoin('client_basicinfo','client_basicinfo.id','=','daily_report.client_id')
            ->leftjoin('candidate_status','candidate_status.id','=','daily_report.candidate_status_id')
            ->select('daily_report.id as id', 'daily_report.position_name as position_name', 'daily_report.location as location',
                'daily_report.cvs_to_tl as cvs_to_tl', 'daily_report.cvs_to_client as cvs_to_client',
                DB::raw('DATE_FORMAT(daily_report.report_date, "%d-%m-%Y") as report_date'),
                'users.name as user_name', 'client_basicinfo.name as client_name',
                'candidate_status.name as candidate_status')
            ->whereBetween('daily_report.report_date', array($dateClass->changeDMYtoYMD($fromDate), $dateClass->changeDMYtoYMD($toDate)))
            ->orderBy('daily_report.report_date','asc')
            ->get();

        $viewVariable = array();
        $viewVariable['dailyReports'] = $dailyReport;
        $viewVariable['fromDate'] = $fromDate;
        $viewVariable['toDate'] = $toDate;

        $sessionFromDate = $this->setsession('fromDate',$dateClass->changeDMYtoYMD($fromDate));
        $sessionToDate = $this->setsession('toDate',$dateClass->changeDMYtoYMD($toDate));

//        print_r($toUser);exit;
        if(isset($toUser) && $toUser != null){
            $toUserDetails = User::whereIn('id',$toUser)->get()->toArray();
            $toemail = $toUserDetails;
        } else {
            $toemail = null;
        }
        if(isset($ccUser) && $ccUser != null){
            $ccUserDetails = User::whereIn('id',$ccUser)->get()->toArray();
            $ccemail = $ccUserDetails;
        } else {
            $ccemail = null;
        }
        if(isset($bccUser) && $bccUser != null){
            $bccUserDetails = User::whereIn('id',$bccUser)->get()->toArray();
            $bccemail = $bccUserDetails;
        } else {
            $bccemail = null;
        }

        return view('adminlte::emails.dailyReport', $viewVariable);

        $view = View::make('adminlte::emails.dailyReport', $viewVariable);
        $message = (string)$view;

        $subject = 'Daily Report';
        $email_type = 'daily_report';
        $to_name = $toemail;
        $cc_name = $ccemail;
        $bcc_name = $bccemail;

        $emailTraking = new EmailsNotifications();
        $emailTraking->module = $email_type;
        $emailTraking->sender_name = $user_id;
        $emailTraking->to = $to_name;
        $emailTraking->cc = $cc_name;
        $emailTraking->bcc = $bcc_name;
        $emailTraking->subject = $subject;
        $emailTraking->message = $message;
        $emailTraking->sent_date = $currentDate;
        $emailTraking->status = 1;
        $emailTrakingStored = $emailTraking->save();

        $mail_array = [];
        $mail_array['to'] = $to_name;
        $mail_array['cc'] = $cc_name;
        $mail_array['bcc'] = $bcc_name;
        $mail_array['subject'] = $subject;

        /*Mail::send('adminlte::emails.dailyReport', $viewVariable, function($message) use ($mail_array) {
            $message->to($mail_array['to'])
                ->cc($mail_array['cc'])
                ->bcc($mail_array['bcc'])
                ->subject($mail_array['subject']);
        });*/

        return redirect('dailyreport/')->with('success','Daily Report Send');

    }
}
