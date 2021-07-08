<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TicketsDiscussion;
use App\TicketsDiscussionDoc;
use App\User;
use App\Events\NotificationMail;
use App\TicketDiscussionPost;
use App\TicketsDiscussionPostDoc;

class TicketsDiscussionController extends Controller
{
    public function index() {

        $tickets_res = TicketsDiscussion::getAllDetails();
        $count = sizeof($tickets_res);
        
        return view('adminlte::ticketDiscussion.index',compact('tickets_res','count'));
    }

    public function create() {

        $action = 'add';

        $question_type = array();
        $question_type['Type 1'] = 'Type 1';
        $question_type['Type 2'] = 'Type 2';

        $selected_question_type = '';

        return view('adminlte::ticketDiscussion.create',compact('action','question_type','selected_question_type'));
    }

    public function store(Request $request) {

        $user_id = \Auth::user()->id;

        $question_type = $request->input('question_type');
        $description = $request->input('description');

        $ticket_discussion = new TicketsDiscussion();
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

        $module = "Tickets Discussion";
        $sender_name = $user_id;
        $to = $loggedin_useremail;
        $cc = implode(",",$cc_users_array);

        $subject = "Tickets Discussion - " . $question_type;
        $message = "Tickets Discussion - " . $question_type;
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

        $question_type = array();
        $question_type['Type 1'] = 'Type 1';
        $question_type['Type 2'] = 'Type 2';

        $selected_question_type = $ticket_res->question_type;

        return view('adminlte::ticketDiscussion.edit',compact('action','question_type','selected_question_type','ticket_res'));
    }

    public function update(Request $request,$id) {

        $question_type = $request->input('question_type');
        $description = $request->input('description');

        $ticket_discussion = TicketsDiscussion::find($id);
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

        // get loggedin_user_email_id
        $loggedin_useremail = User::getUserEmailById($user_id);

        // get superadmin email id
        $superadminuserid = getenv('SUPERADMINUSERID');

        $cc1 = 'info@adlertalent.com';
        $cc2 = 'saloni@trajinfotech.com';
        $cc3 = 'dhara@trajinfotech.com';

        $superadminemail = User::getUserEmailById($superadminuserid);

        $cc_users_array = array($superadminemail,$cc1,$cc2,$cc3);

        $module = "Tickets Discussion Comment";
        $sender_name = $user_id;
        $to = $loggedin_useremail;
        $cc = implode(",",$cc_users_array);

        $subject = "Tickets Discussion Comment";
        $message = "Tickets Discussion Comment";
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

       return redirect()->route('ticket.remarks',[$tickets_discussion_id]);
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
}