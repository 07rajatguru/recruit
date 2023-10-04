<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailTemplate;
use Illuminate\Support\Facades\Crypt;
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
        $superadminemail = User::getUserEmailById($superadminuserid);

        $to_users_array = User::getAllUserEmailsByID($users);

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

        $id = Crypt::decrypt($id);

    	$email_template = EmailTemplate::find($id);

        $email_template_users = \DB::table('email_template_visible_users')
        ->join('users','users.id','=','email_template_visible_users.user_id')->select('users.id', 'users.name as name')->where('email_template_visible_users.email_template_id',$id)->get();

        if(isset($email_template_users) && sizeof($email_template_users) > 0) {

            $c = 0;
            foreach ($email_template_users as $key => $value) {
                $email_template_users_list[$c] = $value->name;
                $c++;
            }
        }
        else {
            $email_template_users_list = array();
        }

    	return view('adminlte::emailtemplate.show',compact('email_template','email_template_users_list'));
    }

    public function edit($id) {
        
        $id = Crypt::decrypt($id);

    	$email_template = EmailTemplate::find($id);
    	$action = 'edit';

        $email_template_users = EmailTemplateVisibleUser::where('email_template_id',$id)->get();
       
        $selected_users = array();
        if(isset($email_template_users) && sizeof($email_template_users)>0)  {
            foreach($email_template_users as $row){
                $selected_users[] = $row->user_id;
            }
        }

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

        $selected_departments = explode(",",$email_template->department_ids);
        $users = User::getAllUsers($selected_departments);
        $email_template_id = $id;

    	return view('adminlte::emailtemplate.edit',compact('email_template','action','users','selected_users','selected_departments','departments','email_template_id'));
    }

    public function update($id,Request $request) {
    	
        $user_id = \Auth::user()->id;

        // Get superadmin user id
        $superadminuserid = getenv('SUPERADMINUSERID');

    	$name = $request->get('name');
    	$subject = $request->get('subject');
    	$email_body = $request->get('email_body');
        $department_ids = $request->input('department_ids');
        $users = $request->input('user_ids');

    	$email_template = EmailTemplate::find($id);
        $email_template->user_id = $user_id;
    	$email_template->name = $name;
    	$email_template->subject = $subject;
    	$email_template->email_body = $email_body;
        $email_template->department_ids = implode(",", $department_ids);
    	$email_template->save();

        if (isset($users) && sizeof($users) > 0) {

            // Delete Previous Users
            EmailTemplateVisibleUser::where('email_template_id',$id)->delete();

            foreach ($users as $key => $value) {

                $email_template_visible_users = new EmailTemplateVisibleUser();
                $email_template_visible_users->email_template_id = $id;
                $email_template_visible_users->user_id = $value;
                $email_template_visible_users->save();
            }

            // Add superadmin user id by default
            $email_template_visible_users = new EmailTemplateVisibleUser();
            $email_template_visible_users->email_template_id = $id;
            $email_template_visible_users->user_id = $superadminuserid;
            $email_template_visible_users->save();
        }

    	return redirect()->route('emailtemplate.index')->with('success','Email Template Updated Successfully.');
    }

    public function destroy($id) {

        EmailTemplateVisibleUser::where('email_template_id',$id)->delete();
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

    public function getUsersByEmailTemplateID() {

        $department_ids = $_GET['department_selected_items'];
        $email_template_id = $_GET['email_template_id'];

        $users = User::getUsersByDepartmentIDArray($department_ids);

        $email_template_users = \DB::table('email_template_visible_users')
        ->join('users','users.id','=','email_template_visible_users.user_id')
        ->select('users.id as user_id', 'users.name as name')
        ->where('email_template_visible_users.email_template_id',$email_template_id)->get();

        $selected_users = array();
        $i=0;

        foreach ($email_template_users as $key => $value) {
            $selected_users[$i] = $value->user_id;
            $i++;       
        }

        $data = array();
        $j=0;

        foreach ($users as $key => $value) {

            if(in_array($value['id'], $selected_users)) {
                $data[$j]['checked'] = '1';
            }
            else {
                $data[$j]['checked'] = '0';
            }
            
            $data[$j]['id'] = $value['id'];
            $data[$j]['type'] = $value['type'];
            $data[$j]['name'] = $value['name'];

            $j++;
        }
        return $data;exit;
    }
}