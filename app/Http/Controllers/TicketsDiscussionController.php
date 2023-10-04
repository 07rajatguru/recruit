<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TicketsDiscussion;
use App\TicketsDiscussionDoc;
use App\User;
use App\Events\NotificationMail;
use App\TicketDiscussionPost;
use App\TicketsDiscussionPostDoc;
use App\Module;

class TicketsDiscussionController extends Controller
{
    public function index() {

        $user =  \Auth::user();
        $all_perm = $user->can('display-ticket');
        $userwise_perm = $user->can('display-user-wise-ticket');
        $hemali_user_id = getenv('HEMALIUSERID');

        if($all_perm || $hemali_user_id == $user->id) {
            $tickets_res = TicketsDiscussion::getAllTicketDetails(1,$user->id,NULL);
        } else if($userwise_perm) {
            $tickets_res = TicketsDiscussion::getAllTicketDetails(0,$user->id,NULL);
        }

        $count = sizeof($tickets_res);
        
        $i = 0;
        $open = '';
        $in_progress = '';
        $closed = '';

        foreach($tickets_res as $ticket) {

            if($ticket['status'] == 'Open') {
                $open++;
            } else if ($ticket['status'] == 'In Progress') {
                $in_progress++;
            } else if($ticket['status'] == 'Closed') {
                $closed++;
            }
        }

        $status_array = TicketsDiscussion::ticketStatus();

        return view('adminlte::ticketDiscussion.index',compact('tickets_res','count','open','in_progress','closed','status_array'));
    }

    public function getAllTicketsByStatus($status) {

        $user =  \Auth::user();
        $all_perm = $user->can('display-ticket');
        $userwise_perm = $user->can('display-user-wise-ticket');
        $hemali_user_id = getenv('HEMALIUSERID');

        if($all_perm || $hemali_user_id == $user->id) {
            $all_tickets_res = TicketsDiscussion::getAllTicketDetails(1,$user->id);
            $tickets_res = TicketsDiscussion::getAllTicketDetails(1,$user->id,$status);
        } else if($userwise_perm) {
            $all_tickets_res = TicketsDiscussion::getAllTicketDetails(0,$user->id);
            $tickets_res = TicketsDiscussion::getAllTicketDetails(0,$user->id,$status);
        }

        $count = sizeof($tickets_res);
        
        $i = 0;
        $open = '';
        $in_progress = '';
        $closed = '';

        foreach($all_tickets_res as $ticket) {

            if($ticket['status'] == 'Open') {
                $open++;
            }
            else if ($ticket['status'] == 'In Progress') {
                $in_progress++;
            }
            else if($ticket['status'] == 'Closed') {
                $closed++;
            }
        }

        $status_array = TicketsDiscussion::ticketStatus();

        return view('adminlte::ticketDiscussion.ticketstatusindex',compact('tickets_res','count','open','in_progress','closed','status','status_array'));
    }

    public function create() {

        $action = 'add';

        $modules = Module::getModules();
        $selected_module = '';

        $question_type = TicketsDiscussion::ticketTicketQuestionType();
        $selected_question_type = '';

        $status = TicketsDiscussion::ticketStatus();
        $selected_status = '';

        $max_id = TicketsDiscussion::find(\DB::table('tickets_discussion')->max('id'));

        if(isset($max_id->id) && $max_id->id != '') {
            $incr_value = $max_id->id + 1; 
        }
        else {
            $incr_value = 1;
        }

        if($incr_value < 10) {

            $ticket_no = 'E2HTIC0' . $incr_value;
        }
        else {

            $ticket_no = 'E2HTIC' . $incr_value;
        }

        return view('adminlte::ticketDiscussion.create',compact('action','question_type','selected_question_type','modules','selected_module','status','selected_status','ticket_no'));
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
        $ticket_discussion->status_date = date('Y-m-d H:i:s');
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

        // Send email notification
        // get loggedin_user_email_id
        $loggedin_useremail = User::getUserEmailById($user_id);
        $to_users_array = array($loggedin_useremail);

        $it1 = 'saloni@trajinfotech.com';
        $it2 = 'dhara@trajinfotech.com';
        $it3 = 'e2hteam01@gmail.com';
        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);
        // get manager email id
        $manager_user_id = env('MANAGERUSERID');
        $manager_email = User::getUserEmailById($manager_user_id);
        // Get Hemali email id
        $hemali_user_id = env('HEMALIUSERID');
        $hemali_email = User::getUserEmailById($hemali_user_id);

        $cc_users_array = array($it1,$it2,$it3,$superadminemail,$manager_email,$hemali_email);

        $module = "Ticket Discussion";
        $sender_name = $user_id;
        $to = implode(",",$to_users_array);
        $cc = implode(",",$cc_users_array);

        $subject = "Ticket Discussion - " . $ticket_no;
        $message = "Ticket Discussion - " . $ticket_no;
        $module_id = $tickets_discussion_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('ticket.index')->with('success','Ticket Generated Successfully.');
    }

    public function show($id) {

        $id = \Crypt::decrypt($id);
        $ticket_res = TicketsDiscussion::getTicketDetailsById($id);
        $ticket_res_doc = TicketsDiscussionDoc::getTicketDocsById($id);

        return view('adminlte::ticketDiscussion.show',compact('ticket_res','ticket_res_doc'));
    }

    public function edit($id) {

        $id = \Crypt::decrypt($id);
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

        $logg_user_id = \Auth::user()->id;
        $ticket_no = $request->input('ticket_no');
        $module_id = $request->input('module_id');
        $status = $request->input('status');
        $question_type = $request->input('question_type');
        $description = $request->input('description');

        $ticket_discussion = TicketsDiscussion::find($id);
        $ticket_discussion->ticket_no = $ticket_no;
        $ticket_discussion->module_id = $module_id;

        // For changes of ticket status
        $old_status = $ticket_discussion->status;
        if (isset($old_status) && $old_status != $status) {
            $ticket_discussion->status_date = date('Y-m-d H:i:s');
            $ticket_discussion->status_changed_by = $logg_user_id;
        }

        $ticket_discussion->status = $status;
        $ticket_discussion->question_type = $question_type;
        $ticket_discussion->description = $description;
        $ticket_discussion->save();

        if ($status == 'Closed') {
            // Send email notification
            // get ticket user email
            $ticket_useremail = User::getUserEmailById($ticket_discussion->added_by);
            $to_users_array = array($ticket_useremail);

            // get loggedin_user_email_id
            $user_id = \Auth::user()->id;
            $loggedin_useremail = User::getUserEmailById($user_id);

            $it1 = 'saloni@trajinfotech.com';
            $it2 = 'dhara@trajinfotech.com';
            $it3 = 'e2hteam01@gmail.com';
            // get superadmin email id
            $superadminuserid = getenv('SUPERADMINUSERID');
            $superadminemail = User::getUserEmailById($superadminuserid);
            // get manager email id
            $manager_user_id = env('MANAGERUSERID');
            $manager_email = User::getUserEmailById($manager_user_id);
            // Get Hemali email id
            $hemali_user_id = env('HEMALIUSERID');
            $hemali_email = User::getUserEmailById($hemali_user_id);

            $cc_users_array = array($it1,$it2,$it3,$superadminemail,$manager_email,$loggedin_useremail,$hemali_email);

            $module = "Ticket Discussion Closed";
            $sender_name = $user_id;
            $to = implode(",",$to_users_array);
            $cc = implode(",",$cc_users_array);

            $subject = "Ticket Discussion Closed - " . $ticket_no;
            $message = "Ticket Discussion Closed - " . $ticket_no;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

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

        $id = \Crypt::decrypt($id);

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

        // Send email notification
        $to1 = 'saloni@trajinfotech.com';
        $to2 = 'dhara@trajinfotech.com';
        $to_users_array = array($to1,$to2);

        // get loggedin_user_email_id
        $loggedin_useremail = User::getUserEmailById($user_id);

        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);
        // get manager email id
        $manager_user_id = env('MANAGERUSERID');
        $manager_email = User::getUserEmailById($manager_user_id);

        $cc_users_array = array($superadminemail,$loggedin_useremail,$manager_email);

        $module = "Ticket Discussion Comment";
        $sender_name = $user_id;
        $to = implode(",",$to_users_array);
        $cc = implode(",",$cc_users_array);

        $subject = "Ticket Discussion Comment - " . $ticket_res['ticket_no'];
        $message = "Ticket Discussion Comment - " . $ticket_res['ticket_no'];
        $module_id = $post_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('ticket.remarks',[\Crypt::encrypt($$tickets_discussion_id)]);
    }

    public function updateRemarks(Request $request, $tickets_discussion_id,$post_id) {

        $input = $request->all();
        $user_id = $input['user_id'];
        $tickets_discussion_id = $input['tickets_discussion_id'];

        $response = TicketDiscussionPost::updatePost($post_id,$input["content"]);
        $returnValue["success"] = true;
        $returnValue["message"] = "Content Updated";
        $returnValue["id"] = $post_id;

       return redirect()->route('ticket.remarks',[\Crypt::encrypt($tickets_discussion_id)])->with('success','Ticket Comment Updated Successfully.');
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

        $user_id = \Auth::user()->id;
        $ticketstatus = $request->get('ticketstatus');
        $id = $request->get('id');
        $status_ticket = TicketsDiscussion::find($id);
        $status = '';

        if (isset($ticketstatus) && $ticketstatus != ''){
            $status = $ticketstatus;
        }

        // For changes of ticket status
        $old_status = $status_ticket->status;
        if (isset($old_status) && $old_status != $status) {
            $status_ticket->status_date = date('Y-m-d H:i:s');
            $status_ticket->status_changed_by = $user_id;
        }

        $status_ticket->status = $status;
        $status_ticket->save();

        if ($status == 'Closed') {
            // Send email notification
            // get ticket user email
            $ticket_useremail = User::getUserEmailById($status_ticket->added_by);
            $to_users_array = array($ticket_useremail);

            // get loggedin_user_email_id
            $loggedin_useremail = User::getUserEmailById($user_id);

            $it1 = 'saloni@trajinfotech.com';
            $it2 = 'dhara@trajinfotech.com';
            $it3 = 'e2hteam01@gmail.com';
            // get superadmin email id
            $superadminuserid = getenv('SUPERADMINUSERID');
            $superadminemail = User::getUserEmailById($superadminuserid);
            // get manager email id
            $manager_user_id = env('MANAGERUSERID');
            $manager_email = User::getUserEmailById($manager_user_id);
            // Get Hemali email id
            $hemali_user_id = env('HEMALIUSERID');
            $hemali_email = User::getUserEmailById($hemali_user_id);

            $cc_users_array = array($it1,$it2,$it3,$superadminemail,$manager_email,$loggedin_useremail,$hemali_email);

            $module = "Ticket Discussion Closed";
            $sender_name = $user_id;
            $to = implode(",",$to_users_array);
            $cc = implode(",",$cc_users_array);

            $subject = "Ticket Discussion Closed - " . $status_ticket->ticket_no;
            $message = "Ticket Discussion Closed - " . $status_ticket->ticket_no;
            $module_id = $id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        return redirect()->route('ticket.index')->with('success', 'Ticket Status Updated Successfully.');
    }
}