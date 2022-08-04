<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\pdfParser;
use App\User;
use App\ProcessManual;
use App\ProcessDoc;
use App\ProcessVisibleUser;
use App\Utils;
use DB;
use App\Events\NotificationMail;
use App\Department;

class ProcessController extends Controller
{
    public function index() {

        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-process-manual');
        $user_perm = $user->can('display-process-manual-added-by-loggedin-user');

        if($all_perm) {

            $process_response = ProcessManual::getAllprocess(1,$user_id);
            $count = ProcessManual::getAllprocessCount(1,$user_id);
        }
        else if($user_perm) {

            $process_response = ProcessManual::getAllprocess(0,$user_id);
            $count = ProcessManual::getAllprocessCount(0,$user_id);
        }

        $viewVariable = array();
        $viewVariable['processList'] = $process_response;
        $viewVariable['count'] = $count;

    	return view('adminlte::process.index ',$viewVariable);
    }

    public function getOrderProcessColumnName($order) {

        $order_column_name = '';
        if (isset($order) && $order >= 0) {
            if ($order == 0) {
                $order_column_name = "process_manual.id";
            }
            else if ($order == 1) {
                $order_column_name = "process_manual.title";
            }
        }
        return $order_column_name;
    }

    public function getAllProcessDetails() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $user = \Auth::user();
        $user_id = $user->id;
        
        $all_perm = $user->can('display-process-manual');
        $user_perm = $user->can('display-process-manual-added-by-loggedin-user');
        $delete_perm = $user->can('process-manual-delete');

        if($all_perm) {
            $order_column_name = self::getOrderProcessColumnName($order);
            $process_response = ProcessManual::getAllprocess(1,$user_id,$limit,$offset,$search,$order_column_name,$type);
            $count = ProcessManual::getAllprocessCount(1,$user_id,$search);
        }
        else if($user_perm) {

            $order_column_name = self::getOrderProcessColumnName($order);
            $process_response = ProcessManual::getAllprocess(0,$user_id,$limit,$offset,$search,$order_column_name,$type);
            $count = ProcessManual::getAllprocessCount(0,$user_id,$search);
        }

        $process = array();
        $i = 0; $j = 0;
        foreach ($process_response as $key => $value) {

            $action = '';

            $action .= '<a title="Show" class="fa fa-circle" href="'.route('process.show',$value['id']).'" style="margin:2px;"></a>';

            if(isset($value['access']) && $value['access']==1){
                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('process.edit',$value['id']).'" style="margin:2px;"></a>';
            }
            if ($delete_perm) {

                $delete_view = \View::make('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'process','display_name'=>'Process']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            $doc_count = ProcessManual::getProcessManualsDocCount($value['id']);

            if($doc_count == 1){
                $title = '<a target="_blank" href="'.$value['url'].'">'.$value['title'].'</a>';
            }
            else{
                $title = $value['title'];
            }
            
            $data = array(++$j,$title,$value['department'],$action);
            $process[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $process
        );

        echo json_encode($json_data);exit;
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

        $all_departments = array();
        $all_department_res = Department::orderBy('id','ASC')->whereIn('id',$type_array)->get();
        if(sizeof($all_department_res) > 0) {
            foreach($all_department_res as $a_r) {
                $all_departments[$a_r->id] = $a_r->name;
            }
        }
        $all_departments = array_fill_keys(array(''),'Select Department')+$all_departments;
        $department_id = '';
        
        $selected_departments = array();
        $process_id = 0;
        
        return view('adminlte::process.create',compact('action','departments','selected_departments','process_id','all_departments','department_id'));
    }

    public function store(Request $request) {

		$user_id = \Auth::user()->id;
        $title = $request->input('title');
        $department_ids = $request->input('department_ids');
        $department = $request->input('department');

        $process = new ProcessManual();
        $process->title = $title;
        $process->department_ids = implode(",", $department_ids);
        $process->owner_id = $user_id;
        $process->department_id = $department;
        $process->save();

        $upload_documents = $request->file('upload_documents');

        //save files in ProcessDoc table
        $process_id = $process->id;

        if (isset($upload_documents) && sizeof($upload_documents) > 0) {

            foreach ($upload_documents as $k => $v) {

                if (isset($v) && $v->isValid()) {
                    
                    $file_name = $v->getClientOriginalName();
                    $file_extension = $v->getClientOriginalExtension();
                    $file_realpath = $v->getRealPath();
                    $file_size = $v->getSize();

                    $dir = 'uploads/process/' . $process_id . '/';

                    if (!file_exists($dir) && !is_dir($dir)) {
                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $v->move($dir, $file_name);

                    $file_path = $dir . $file_name;

                    $process_doc = new ProcessDoc();
                    $process_doc->process_id = $process_id;
                    $process_doc->file = $file_path;
                    $process_doc->name = $file_name;
                    $process_doc->size = $file_size;
                    $process_doc->created_at = date('Y-m-d');
                    $process_doc->updated_at = date('Y-m-d');
                    $process_doc->save();
                }
            }
        }
        // insert in Process_Visible_Table

        $users = $request->input('user_ids');

        if(isset($users) && sizeof($users)>0) {

            foreach ($users as $key => $value) {

                $process_visible_users = new ProcessVisibleUser();
                $process_visible_users->process_id = $process_id;
                $process_visible_users->user_id = $value;
                $process_visible_users->save();
            }

            // Add superadmin user id of management department
 
            $superadminuserid = getenv('SUPERADMINUSERID');

            $process_visible_users = new ProcessVisibleUser();
            $process_visible_users->process_id = $process_id;
            $process_visible_users->user_id = $superadminuserid;
            $process_visible_users->save();
        }

        // Check Process Manual for all users or not and update select_all field in database
        $users_id = User::getAllUsers();
        $user_count = sizeof($users_id);

        if(isset($users)) {
            $process_users = sizeof($users);
        }
        else {
            $process_users = 0;
        }

        if ($process_users == $user_count) {
            \DB::statement("UPDATE process_manual SET select_all = '1' where id=$process_id");
        }
        else {
            \DB::statement("UPDATE process_manual SET select_all = '0' where id=$process_id");
        }

        //Email Notification : data store in database
        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        if (isset($users) && sizeof($users)>0) {

            foreach ($users as $key => $value) {
                $email = User::getUserEmailById($value);
                $user_emails[] = $email;
            }
        }

        $module = "Process Manual";
        $sender_name = $user_id;
        $to = implode(",",$user_emails);
        $cc = $superadminemail;

        $subject = "Process Manual - ". $title;
        $message = "Process Manual - ". $title;
        $module_id = $process_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

    	return redirect()->route('process.index')->with('success','Process Manual Created Successfully.');
    }

    public function edit($id) {

     	$process = ProcessManual::find($id);
       
        $action = "edit";

        $i = 0;
        $processdetails['files'] = array();

        $processFiles = ProcessDoc::select('process_doc.*')->where('process_doc.process_id',$id)
        ->orderBy('process_doc.id','asc')->get();

        $utils = new Utils();

        if(isset($processFiles) && sizeof($processFiles) > 0) {

            foreach ($processFiles as $processFile) {

                $processdetails['files'][$i]['id'] = $processFile->id;
                $processdetails['files'][$i]['fileName'] = $processFile->file;
                $processdetails['files'][$i]['url'] = "../../".$processFile->file;
                $processdetails['files'][$i]['name'] = $processFile->name ;
                $processdetails['files'][$i]['size'] = $utils->formatSizeUnits($processFile->size);

                $i++;
            }
        }

        $process_visible_users = ProcessVisibleUser::where('process_id',$id)->get();
       
        $selected_users = array();
        if(isset($process_visible_users) && sizeof($process_visible_users)>0) {
            foreach($process_visible_users as $row) {
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

        $selected_departments = explode(",",$process->department_ids);

        $all_departments = array();
        $all_department_res = Department::orderBy('id','ASC')->whereIn('id',$type_array)->get();
        if(sizeof($all_department_res) > 0) {
            foreach($all_department_res as $a_r) {
                $all_departments[$a_r->id] = $a_r->name;
            }
        }
        $all_departments = array_fill_keys(array(''),'Select Department')+$all_departments;
        $department_id = $process->department_id;

        $users = User::getAllUsers($selected_departments);

        $process_id = $id;

        return view('adminlte::process.edit',compact('action','users','selected_users','process','processdetails','selected_departments','departments','process_id','all_departments','department_id'));
     }

     public function update(Request $request,$id) {
        
        $department_ids = $request->input('department_ids');
        $department = $request->input('department');
        
        $process = ProcessManual::find($id);
        $process->title = $request->input('title');
        $process->department_ids = implode(",", $department_ids);
        $process->department_id = $department;
        $process->save();

        $file = $request->file('file');
        //save files 
        $process_id = $process->id;     
        if (isset($file) && $file != '') {

            if (isset($file) && $file->isValid()) {

                $file_name = $file->getClientOriginalName();
                $file_extension = $file->getClientOriginalExtension();
                $file_realpath = $file->getRealPath();
                $file_size = $file->getSize();

                $dir = 'uploads/process/' . $process_id . '/';

                if (!file_exists($dir) && !is_dir($dir)) {
                    mkdir($dir, 0777, true);
                    chmod($dir, 0777);
                }
                $file->move($dir, $file_name);

                $file_path = $dir . $file_name;
                $process_doc = new ProcessDoc();
                $process_doc->process_id = $process_id;
                $process_doc->file = $file_path;
                $process_doc->name = $file_name;
                $process_doc->size = $file_size;
                $process_doc->created_at = date('Y-m-d');
                $process_doc->updated_at = date('Y-m-d');
			    $process_doc->save();
            }
            return redirect()->route('process.edit',[$process_id])->with('success','Attachment Uploaded Successfully.'); 
        }

        // Update in process visible table
        $users = $request->input('user_ids');
        ProcessVisibleUser::where('process_id',$process_id)->delete();

        if(isset($users) && sizeof($users)>0) {

            foreach ($users as $key => $value) {

                $process_visible_users = new ProcessVisibleUser();
                $process_visible_users->process_id = $process_id;
                $process_visible_users->user_id = $value;
                $process_visible_users->save();
            }

            // Add superadmin user id of management department
 
            $superadminuserid = getenv('SUPERADMINUSERID');

            $process_visible_users = new ProcessVisibleUser();
            $process_visible_users->process_id = $process_id;
            $process_visible_users->user_id = $superadminuserid;
            $process_visible_users->save();
        }

        // Check Process Manual for all users or not and update select_all field in database
        $users_id = User::getAllUsers();
        $user_count = sizeof($users_id);

        $process_users = sizeof($users);
        if ($process_users == $user_count) {
            \DB::statement("UPDATE process_manual SET select_all = '1' where id=$process_id");
        }
        else {
            \DB::statement("UPDATE process_manual SET select_all = '0' where id=$process_id");
        }

        return redirect()->route('process.index')->with('success','Process Manual Updated Successfully.');
    }

	public function upload(Request $request) {

        $file = $request->file('file');
        $process_id = $request->id;

        $user_id = \Auth::user()->id;

        if(isset($file) && $file->isValid()) {

            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            $dir = 'uploads/process/'.$process_id.'/';

            if (!file_exists($dir) && !is_dir($dir)) {

                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $file->move($dir,$fileName);
            $filePath = $dir . $fileName;

            $processFileUpload = new ProcessDoc();
            $processFileUpload->process_id = $process_id;
            $processFileUpload->file = $filePath;
            $processFileUpload->name = $fileName;
            $processFileUpload->size = $fileSize;
            $processFileUpload->save();
        }

        return redirect()->route('process.show',[$process_id])->with('success','Attachment Uploaded Successfully.');
    }

    public function show($id) {
		
        $process_res = \DB::table('process_manual')
        ->select('process_manual.*')->where('process_manual.id','=',$id)->first();

        $process = array();

        $process['id'] = $process_res->id;
        $process['title'] = $process_res->title;

        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-process-manual');

        if($all_perm){
            $process['access'] = '1';
        }
        else{
            if((isset($process_res->owner_id) && $process_res->owner_id == $user_id)) {
                $process['access'] = '1';
            }
            else{
                $process['access'] = '0';
            }
        }

        $process_user_res = \DB::table('process_visible_users')
        ->join('users','users.id','=','process_visible_users.user_id')
        ->select('users.id', 'users.name as name')->where('process_visible_users.process_id',$id)->get();

        $c = 0;
        foreach ($process_user_res as $key => $value) {
            $process['name'][$c] = $value->name;
            $c++;
        }

		$i = 0;
        $processdetails['files'] = array();
        $processFiles = ProcessDoc::select('process_doc.*')->where('process_doc.process_id',$id)
        ->orderBy('process_doc.id','asc')->get();

        $utils = new Utils();

        if(isset($processFiles) && sizeof($processFiles) > 0) {

            foreach ($processFiles as $processFile) {

                $processdetails['files'][$i]['id'] = $processFile->id;
                $processdetails['files'][$i]['fileName'] = $processFile->file;
                $processdetails['files'][$i]['url'] = "../../".$processFile->file;
                $processdetails['files'][$i]['name'] = $processFile->name ;
                $processdetails['files'][$i]['size'] = $utils->formatSizeUnits($processFile->size);

                $i++;
            }
        }
        $department_name = Department::getDepartmentNameById($process_res->department_id);
        return view('adminlte::process.show',compact('processdetails','process','department_name'));
    }
    
    public function processDestroy($id) {

        ProcessDoc::where('process_id',$id)->delete();
        ProcessVisibleUser::where('process_id',$id)->delete();
        ProcessManual::where('id',$id)->delete();

        return redirect()->route('process.index')->with('success','Process Deleted Successfully');
    }


    public function attachmentsDestroy(Request $request,$docid) {

        $type = $request->input('type');

        $process_attch = \DB::table('process_doc')
        ->select('process_doc.*')
        ->where('id','=',$docid)->first();

        if(isset($process_attch)) {

            $path = "uploads/process/".$process_attch->process_id ."/". $process_attch->name;
            unlink($path);
        }

        $id = $_POST['id'];

        ProcessDoc::where('id',$docid)->delete();

        if($type == 'Edit') {
            return redirect()->route('process.edit',[$id])->with('success','Attachment Deleted Successfully.');
        }
        else {
            return redirect()->route('process.show',[$id])->with('success','Attachment Deleted Successfully.');
        }
    }

    public function UpdatePosition() {

        $ids_array = explode(",", $_GET['ids']);

        $i = 1;
        foreach ($ids_array as $id) {

            $order = ProcessManual::find($id);
            $order->position = $i;
            $order->save();
            $i++;
        }
    }

    public function getUsersByProcessID() {

        $department_ids = $_GET['department_selected_items'];
        $process_id = $_GET['process_id'];

        $users = User::getUsersByDepartmentIDArray($department_ids);

        $process_user_res = \DB::table('process_visible_users')
        ->join('users','users.id','=','process_visible_users.user_id')
        ->select('users.id as user_id', 'users.name as name')
        ->where('process_visible_users.process_id',$process_id)->get();

        $selected_users = array();
        $i=0;

        foreach ($process_user_res as $key => $value) {
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