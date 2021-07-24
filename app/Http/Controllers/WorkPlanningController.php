<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WorkPlanning;
use App\WorkPlanningList;
use App\UsersLog;

class WorkPlanningController extends Controller
{
    public function index() {

        $user =  \Auth::user();
        $all_perm = $user->can('display-work-planning');
        $userwise_perm = $user->can('display-user-wise-work-planning');

        if($all_perm) {

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(1,$user->id);
        }
        else if($userwise_perm) {

            $work_planning_res = WorkPlanning::getWorkPlanningDetails(0,$user->id);
        }

        $count = sizeof($work_planning_res);

        return view('adminlte::workPlanning.index',compact('work_planning_res','count'));
    }

    public function create() {

        $action = 'add';

        $work_type = WorkPlanning::getWorkType();
        $selected_work_type = '';

        $user_id = \Auth::user()->id;
        $date = date('Y-m-d');

        // Get Logged in Log out Time

        $get_time = UsersLog::getUserLogInTime($user_id,$date);

        // Convert Logged in time
        $utc_login = $get_time['login'];
        $dt_login = new \DateTime($utc_login);
        $tz_login = new \DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

        $dt_login->setTimezone($tz_login);
        $loggedin_time = $dt_login->format('H:i:s');

        // Convert Logged out time
        $utc_logout = $get_time['logout'];
        $dt_logout = new \DateTime($utc_logout);
        $tz_logout = new \DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

        $dt_logout->setTimezone($tz_logout);
        $loggedout_time = $dt_logout->format('H:i:s');

        return view('adminlte::workPlanning.create',compact('action','work_type','selected_work_type','loggedin_time','loggedout_time'));
    }

    public function store(Request $request) {

        $user_id = \Auth::user()->id;

        $ticket_no = $request->input('ticket_no');
        $module_id = $request->input('module_id');
        $status = $request->input('status');
        $question_type = $request->input('question_type');
        $description = $request->input('description');

        $ticket_discussion = new TicketsDiscussion();
        $ticket_discussion->ticket_no = $ticket_no;
        $ticket_discussion->module_id = $module_id;
        $ticket_discussion->status = $status;
        $ticket_discussion->question_type = $question_type;
        $ticket_discussion->description = $description;
        $ticket_discussion->added_by = $user_id;
        $ticket_discussion->save();

        $upload_documents = $request->file('upload_documents');

        $tickets_discussion_id = $ticket_discussion->id;

        if (isset($upload_documents) && sizeof($upload_documents) > 0) {

            foreach ($upload_documents as $k => $v) {

                if (isset($v) && $v->isValid()) {

                    $file_name = $v->getClientOriginalName();
                    $file_extension = $v->getClientOriginalExtension();
                    $file_realpath = $v->getRealPath();
                    $file_size = $v->getSize();

                    $dir = 'uploads/ticket_discussion/' . $tickets_discussion_id . '/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }

                    $v->move($dir, $file_name);

                    $file_path = $dir . $file_name;

                    $ticket_discussion_doc = new TicketsDiscussionDoc();
                    $ticket_discussion_doc->tickets_discussion_id = $tickets_discussion_id;
                    $ticket_discussion_doc->file = $file_path;
                    $ticket_discussion_doc->name = $file_name;
                    $ticket_discussion_doc->size = $file_size;
                    $ticket_discussion_doc->uploaded_by = $user_id;
                    $ticket_discussion_doc->created_at = date('Y-m-d');
                    $ticket_discussion_doc->updated_at = date('Y-m-d');
                    $ticket_discussion_doc->save();
                }
            }
        }

        // get loggedin_user_email_id
        $loggedin_useremail = User::getUserEmailById($user_id);

        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');

        $cc1 = 'info@adlertalent.com';
        $cc2 = 'saloni@trajinfotech.com';
        $cc3 = 'dhara@trajinfotech.com';

        $superadminemail = User::getUserEmailById($superadminuserid);

        $cc_users_array = array($superadminemail,$cc1,$cc2,$cc3);

        $module = "Ticket Discussion";
        $sender_name = $user_id;
        $to = $loggedin_useremail;
        $cc = implode(",",$cc_users_array);

        $subject = "Ticket Discussion - " . $ticket_no;
        $message = "Ticket Discussion - " . $ticket_no;
        $module_id = $tickets_discussion_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('ticket.index')->with('success','Ticket Generated Successfully.');
    }

    public function show($id) {

        $ticket_res = TicketsDiscussion::getTicketDetailsById($id);
        $ticket_res_doc = TicketsDiscussionDoc::getTicketDocsById($id);

        return view('adminlte::ticketDiscussion.show',compact('ticket_res','ticket_res_doc'));
    }

    public function edit($id) {

        $action = 'edit';
        
        $ticket_res = TicketsDiscussion::find($id);

        $modules = Module::getModules();
        $selected_module = $ticket_res->module_id;

        $question_type = TicketsDiscussion::ticketTicketQuestionType();
        $selected_question_type = $ticket_res->question_type;

        $status = TicketsDiscussion::ticketStatus();
        $selected_status = $ticket_res->status;

        $ticket_no = $ticket_res->ticket_no;

        return view('adminlte::ticketDiscussion.edit',compact('action','question_type','selected_question_type','ticket_res','modules','selected_module','status','selected_status','ticket_no'));
    }

    public function update(Request $request,$id) {

        $ticket_no = $request->input('ticket_no');
        $module_id = $request->input('module_id');
        $status = $request->input('status');
        $question_type = $request->input('question_type');
        $description = $request->input('description');

        $ticket_discussion = TicketsDiscussion::find($id);
        $ticket_discussion->ticket_no = $ticket_no;
        $ticket_discussion->module_id = $module_id;
        $ticket_discussion->status = $status;
        $ticket_discussion->question_type = $question_type;
        $ticket_discussion->description = $description;
        $ticket_discussion->save();

        return redirect()->route('ticket.index')->with('success','Ticket Updated Successfully.');
    }

    public function destroy($id) {

        $path = "uploads/ticket_discussion/" . $id . "/";
        $files = glob($path . "/*");

        foreach ($files as $file_nm) {
            unlink($file_nm);
        }

        if(is_dir($path)) {
            rmdir($path);
        }

        TicketsDiscussionDoc::where('tickets_discussion_id','=',$id)->delete();
        TicketsDiscussion::where('id','=',$id)->delete();

        return redirect()->route('ticket.index')->with('success','Ticket Deleted Successfully.');
    }

    public function upload(Request $request) {

        $user_id = \Auth::user()->id;
        $upload_documents = $request->file('file');

        $tickets_discussion_id = $request->id;

        if (isset($upload_documents) && sizeof($upload_documents) > 0) {

            foreach ($upload_documents as $k => $v) {

                if (isset($v) && $v->isValid()) {

                    $file_name = $v->getClientOriginalName();
                    $file_extension = $v->getClientOriginalExtension();
                    $file_realpath = $v->getRealPath();
                    $file_size = $v->getSize();

                    $dir = 'uploads/ticket_discussion/' . $tickets_discussion_id . '/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }

                    $v->move($dir, $file_name);

                    $file_path = $dir . $file_name;

                    $ticket_discussion_doc = new TicketsDiscussionDoc();
                    $ticket_discussion_doc->tickets_discussion_id = $tickets_discussion_id;
                    $ticket_discussion_doc->file = $file_path;
                    $ticket_discussion_doc->name = $file_name;
                    $ticket_discussion_doc->size = $file_size;
                    $ticket_discussion_doc->uploaded_by = $user_id;
                    $ticket_discussion_doc->created_at = date('Y-m-d');
                    $ticket_discussion_doc->updated_at = date('Y-m-d');
                    $ticket_discussion_doc->save();
                }
            }
        }
        return redirect()->route('ticket.show',[$tickets_discussion_id])->with('success','Attachment Uploaded Successfully.');
    }

    public function attachmentsDestroy($docid,Request $request) {

        $tickets_discussion_doc = \DB::table('tickets_discussion_doc')
        ->select('tickets_discussion_doc.*')->where('id','=',$docid)->first();

        if(isset($tickets_discussion_doc)) {

            $path = 'uploads/ticket_discussion/' . $tickets_discussion_doc->tickets_discussion_id . '/' . $tickets_discussion_doc->name;
            unlink($path);
        }

        $tickets_discussion_id = $tickets_discussion_doc->tickets_discussion_id;
        TicketsDiscussionDoc::where('id',$docid)->delete();

        return redirect()->route('ticket.show',[$tickets_discussion_id])->with('success','Attachment Deleted Successfully.');
    }

    public function remarks($id) {

        $user_id = \Auth::user()->id;
        $tickets_discussion_id = $id;

        $ticket_discussion = TicketsDiscussion::find($tickets_discussion_id);
        $post = $ticket_discussion->post()->orderBy('created_at', 'desc')->get();

        return view('adminlte::ticketDiscussion.remarks',compact('user_id','tickets_discussion_id','ticket_discussion','post'));
    }

    public function writePost(Request $request, $tickets_discussion_id) {

        $input = $request->all();
        $user_id = $input['user_id'];
        $tickets_discussion_id = $input['tickets_discussion_id'];
        $content = $input['content'];

        if(isset($user_id) && $user_id > 0) {

            $post = new TicketDiscussionPost();
            $post->content = $content;
            $post->user_id = $user_id;
            $post->tickets_discussion_id = $tickets_discussion_id;
            $post->created_at = time();
            $post->updated_at = time();
            $post->save();
        }

        $post_id = $post->id;

        $upload_documents = $request->file('upload_documents');

        if (isset($upload_documents) && sizeof($upload_documents) > 0) {

            foreach ($upload_documents as $k => $v) {

                if (isset($v) && $v->isValid()) {

                    $file_name = $v->getClientOriginalName();
                    $file_extension = $v->getClientOriginalExtension();
                    $file_realpath = $v->getRealPath();
                    $file_size = $v->getSize();

                    $dir = 'uploads/ticket_discussion/' . $tickets_discussion_id . '/';

                    if (!file_exists($dir) && !is_dir($dir)) {

                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }

                    $v->move($dir, $file_name);

                    $file_path = $dir . $file_name;

                    $ticket_discussion_doc = new TicketsDiscussionPostDoc();
                    $ticket_discussion_doc->tickets_discussion_id = $tickets_discussion_id;
                    $ticket_discussion_doc->post_id = $post_id;
                    $ticket_discussion_doc->file = $file_path;
                    $ticket_discussion_doc->name = $file_name;
                    $ticket_discussion_doc->size = $file_size;
                    $ticket_discussion_doc->uploaded_by = $user_id;
                    $ticket_discussion_doc->created_at = date('Y-m-d');
                    $ticket_discussion_doc->updated_at = date('Y-m-d');
                    $ticket_discussion_doc->save();
                }
            }
        }

        // Get Ticket informations

        $ticket_res = TicketsDiscussion::getTicketDetailsById($tickets_discussion_id);

        // get loggedin_user_email_id
        $loggedin_useremail = User::getUserEmailById($user_id);

        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');

        $cc1 = 'info@adlertalent.com';
        $cc2 = 'saloni@trajinfotech.com';
        $cc3 = 'dhara@trajinfotech.com';

        $superadminemail = User::getUserEmailById($superadminuserid);

        $cc_users_array = array($superadminemail,$cc1,$cc2,$cc3);

        $module = "Ticket Discussion Comment";
        $sender_name = $user_id;
        $to = $loggedin_useremail;
        $cc = implode(",",$cc_users_array);

        $subject = "Ticket Discussion Comment - " . $ticket_res['ticket_no'];
        $message = "Ticket Discussion Comment - " . $ticket_res['ticket_no'];
        $module_id = $post_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('ticket.remarks',[$tickets_discussion_id]);
    }

    public function updateRemarks(Request $request, $tickets_discussion_id,$post_id) {

        $input = $request->all();
        $user_id = $input['user_id'];
        $tickets_discussion_id = $input['tickets_discussion_id'];

        $response = TicketDiscussionPost::updatePost($post_id,$input["content"]);
        $returnValue["success"] = true;
        $returnValue["message"] = "Content Updated";
        $returnValue["id"] = $post_id;

       return redirect()->route('ticket.remarks',[$tickets_discussion_id])->with('success','Ticket Comment Updated Successfully.');
    }

    public function postDestroy($id) {

        $response['returnvalue'] = 'invalid';
        $res = TicketDiscussionPost::deletePost($id);

        if($res) {
            $response['returnvalue'] = 'valid';
        }

        $tickets_discussion_id = $_POST['tickets_discussion_id'];

        return json_encode($response);exit;
    }

    public function changeTicketstatus(Request $request) {

        $ticketstatus = $request->get('ticketstatus');
        $id = $request->get('id');
        $status_ticket = TicketsDiscussion::find($id);
        $status = '';

        if (isset($ticketstatus) && $ticketstatus != ''){
            $status = $ticketstatus;
        }
        $status_ticket->status = $status;
        $status_ticket->save();

        return redirect()->route('ticket.index')->with('success', 'Ticket Status Updated Successfully.');
    }
}