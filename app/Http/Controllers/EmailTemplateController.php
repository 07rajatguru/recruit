<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailTemplate;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Events\NotificationMail;
use App\EmailTemplateVisibleUser;
use App\Utils;
use DB;
use App\Department;

class EmailTemplateController extends Controller
{
    public function index() {

    	$email_template = EmailTemplate::getAllEmailTemplates();
    	$count = sizeof($email_template);

    	return view('adminlte::emailtemplate.index',compact('email_template','count'));
    }

    public function create() {

    	$action = 'add';

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $operations = getenv('OPERATIONS');
        $strategy = getenv('STRATEGY_DEPT');
        $type_array = array($recruitment,$hr_advisory,$operations,$strategy);

        $department_res = Department::orderBy('id','ASC')->whereIn('id',$type_array)->get();
        $departments = array();

        if(sizeof($department_res) > 0) {
            foreach($department_res as $r) {
                $departments[$r->id] = $r->name;
            }
        }
        
        $selected_departments = array();
        $email_template_id = 0;

    	return view('adminlte::emailtemplate.create',compact('action','departments','selected_departments','email_template_id'));
    }

    public function uploadEmailbodyImage(Request $request) {

        $user_id = \Auth::user()->id;

        $CKEditor = $request->input('CKEditor');
        $funcNum  = $request->input('CKEditorFuncNum');
        $message  = $url = '';

        if (Input::hasFile('upload')) {

            $file = Input::file('upload');
            if ($file->isValid()) {

                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/uploads/users/'.$user_id.'/email_body/', $filename);
                $url = url('uploads/users/'.$user_id.'/email_body/' . $filename);
                $message = 'Image Upload Successfully.';
            }
            else {
                $message = 'An error occurred while uploading the file.';
            }
        }
        else {
            $message = 'No file uploaded.';
        }

        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
    }

    public function store(Request $request) {

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;

    	$name = $request->get('name');
    	$subject = $request->get('subject');
    	$email_body = $request->get('email_body');
        $department_ids = $request->input('department_ids');
        
    	$email_template = new EmailTemplate();
        $email_template->user_id = $user_id;
    	$email_template->name = $name;
    	$email_template->subject = $subject;
    	$email_template->email_body = $email_body;
        $email_template->department_ids = implode(",", $department_ids);
    	$email_template->save();

        // Get superadmin user id
        $superadminuserid = getenv('SUPERADMINUSERID');
        $email_template_id = $email_template->id;

        $users = $request->input('user_ids');

        if (isset($users) && sizeof($users)>0) {

            foreach ($users as $key => $value) {

                $email_template_visible_users = new EmailTemplateVisibleUser();
                $email_template_visible_users->email_template_id = $email_template_id;
                $email_template_visible_users->user_id = $value;
                $email_template_visible_users->save();
            }

            // Add superadmin user id by default
            $email_template_visible_users = new EmailTemplateVisibleUser();
            $email_template_visible_users->email_template_id = $email_template_id;
            $email_template_visible_users->user_id = $superadminuserid;
            $email_template_visible_users->save();
        }

        // Send email notification
        $superadminemail = User::getUserEmailById($super_admin_userid);

        $to_users_array = User::getAllUserEmailsByID();

        $module = "Email Template";
        $sender_name = $user_id;
        $to = implode(",",$to_users_array);
        $subject = "New Email Template - " . $name;
        $message = "<tr><td>" . $user_name . " added new Email Template</td></tr>";
        $module_id = $email_template_id;
        $cc = $superadminemail;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

    	return redirect()->route('emailtemplate.index')->with('success','Email Template Added Successfully.');
    }

    public function show($id) {

    	$email_template = EmailTemplate::find($id);

        $email_template_users = \DB::table('email_template_visible_users')
        ->join('users','users.id','=','email_template_visible_users.user_id')->select('users.id', 'users.name as name')->where('email_template_visible_users.email_template_id',$id)->get();

        $c = 0;
        foreach ($email_template_users as $key => $value) {
            $email_template['user_names'][$c] = $value->name;
            $c++;
        }

    	return view('adminlte::emailtemplate.show',compact('email_template'));
    }

    public function edit($id) {

    	$email_template = EmailTemplate::find($id);
    	$action = 'edit';

    	return view('adminlte::emailtemplate.edit',compact('email_template','action'));
    }

    public function update($id,Request $request) {
    	
        $user_id = \Auth::user()->id;

    	$name = $request->get('name');
    	$subject = $request->get('subject');
    	$email_body = $request->get('email_body');

    	$email_template = EmailTemplate::find($id);
        $email_template->user_id = $user_id;
    	$email_template->name = $name;
    	$email_template->subject = $subject;
    	$email_template->email_body = $email_body;
    	$email_template->save();

    	return redirect()->route('emailtemplate.index')->with('success','Email Template Updated Successfully.');
    }

    public function destroy($id) {

    	EmailTemplate::where('id',$id)->delete();

    	return redirect()->route('emailtemplate.index')->with('success','Email Template Deleted Successfully.');
    }

    public function getEmailTemplateById() {
        
        $template_id = $_GET['email_template_id'];

        if($template_id > 0) {
            $template_details = EmailTemplate::getEmailTemplateDetailsById($template_id);
        }
        else {
            $template_details = array();
        }

        return json_encode($template_details);exit;
    }

    public function storeNewEmailTemplate() {

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;

        $template_nm = $_POST['template_nm'];
        $email_subject = $_POST['email_subject'];
        $email_body = $_POST['email_body'];

        $email_template = new EmailTemplate();
        $email_template->user_id = $user_id;
        $email_template->name = $template_nm;
        $email_template->subject = $email_subject;
        $email_template->email_body = $email_body;
        $email_template->save();

        // Send email notification

        $email_template_id = $email_template->id;

        $super_admin_userid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($super_admin_userid);

        $to_users_array = User::getAllUsersEmails(NULL,NULL,'Yes',0);

        $module = "Email Template";
        $sender_name = $user_id;
        $to = implode(",",$to_users_array);
        $subject = "New Email Template - " . $template_nm;
        $message = "<tr><td>" . $user_name . " added new Email Template</td></tr>";
        $module_id = $email_template_id;
        $cc = $superadminemail;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        $data = "Success";
        return json_encode($data);
    }
}