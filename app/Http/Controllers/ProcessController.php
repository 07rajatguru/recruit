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

class ProcessController extends Controller
{
    public function index(Request $request){

     // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();

        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $process_response = ProcessManual::getAllprocess(1,$user_id);
        }
        else{
            $process_response = ProcessManual::getAllprocess(0,$user_id);
        }

        $count = sizeof($process_response);
	    $processFiles = ProcessDoc::select('process_doc.file')->get();

        $viewVariable = array();
        $viewVariable['processList'] = $process_response;
        $viewVariable['isSuperAdmin'] = $isSuperAdmin;
        $viewVariable['processFiles'] = $processFiles;
        $viewVariable['count'] = $count;   

        //$process = ProcessManual::All();


    	return view('adminlte::process.index ',$viewVariable);
    }

    public function create(){

		$action = 'add';

        $user = \Auth::user();
        $user_id = $user->id;

        $users = User::getAllUsers('recruiter');
        $super_admin_user_id = getenv('SUPERADMINUSERID');
        $selected_users = array($user_id,$super_admin_user_id);
        
        return view('adminlte::process.create',compact('action','users','selected_users','user_id'));

    }

    public function store(Request $request){

    	//$users = User::getAllUsers('recruiter');
		$user_id = \Auth::user()->id;
        
        $process = new ProcessManual();
        $process->title = $request->input('title');
        $process->owner_id = $user_id;
        $processStored  = $process->save();

        $upload_documents = $request->file('upload_documents');
        //save files in ProcessDoc table
        $process_id = $process->id;

        if (isset($upload_documents) && sizeof($upload_documents) > 0) {
                foreach ($upload_documents as $k => $v) {
                    if (isset($v) && $v->isValid()) {
                        // echo "here";
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
            $process_id = $process->id;
            if(isset($users) && sizeof($users)>0){
                foreach ($users as $key=>$value){
                    $process_visible_users = new ProcessVisibleUser();
                    $process_visible_users->process_id = $process_id;
                    $process_visible_users->user_id = $value;
                    $process_visible_users->save();
                }
            }

//echo '<pre>';print_r($process_visible_users);die;
    	return redirect()->route('process.index')->with('success','Process Manual Created Successfully');
    }

    public function edit($id){

     	$users = User::getAllUsers('recruiter');
     	$process = ProcessManual::find($id);
       
        $action = "edit" ;

        $i = 0;
        $processdetails['files'] = array();
        $processFiles = ProcessDoc::select('process_doc.*')
                ->where('process_doc.process_id',$id)
                ->get();

        $utils = new Utils();
            if(isset($processFiles) && sizeof($processFiles) > 0){
                foreach ($processFiles as $processFile) {
                $processdetails['files'][$i]['id'] = $processFile->id;
                $processdetails['files'][$i]['fileName'] = $processFile->file;
                $processdetails['files'][$i]['url'] = "../../".$processFile->file;
                $processdetails['files'][$i]['name'] = $processFile->name ;
                $processdetails['files'][$i]['size'] = $utils->formatSizeUnits($processFile->size);

                    $i++;

                }
            }
            // get all users
        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $user_id = $user->id;
        
        $process_visible_users =ProcessVisibleUser::where('process_id',$id)->get();
       // echo'<pre>'; print_r($process_visible_users);die;
       
        $selected_users = array();
        if(isset($process_visible_users) && sizeof($process_visible_users)>0){
            foreach($process_visible_users as $row){
                $selected_users[] = $row->user_id;
            }
        }
		//print_r($selected_users);die;
        return view('adminlte::process.edit',compact('action','users','process','processdetails','selected_users'));
     }

    public function update(Request $request,$id){
        
        //$users = User::getAllUsers();
     	$user_id = \Auth::user()->id;
        
        $process = ProcessManual::find($id);
        $process->title = $request->input('title');
        $process->owner_id = $user_id;
        $processStored  = $process->save();

        $file = $request->file('file');
        //save files 
        $process_id = $process->id;         
        if (isset($file) && sizeof($file) > 0) {
                    if (isset($file) && $file->isValid()) {
                        // echo "here";
                        $file_name = $file->getClientOriginalName();
                        $file_extension = $file->getClientOriginalExtension();
                        $file_realpath = $file->getRealPath();
                        $file_size = $file->getSize();

                        //$extention = File::extension($file_name);

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
                    return redirect()->route('process.edit',[$process_id]); 
        }

        // Update in process visible table
            $users = $request->input('user_ids');
            $process_id = $process->id;
            ProcessVisibleUser::where('process_id',$process_id)->delete();
            if(isset($users) && sizeof($users)>0){
                foreach ($users as $key=>$value){
                    $process_visible_users = new ProcessVisibleUser();
                    $process_visible_users->process_id = $process_id;
                    $process_visible_users->user_id = $value;
                    $process_visible_users->save();
                }
            }
            //echo '<pre>';print_r($process_visible_users);die;
        return redirect()->route('process.index')->with('success','Process Manual Updated Successfully');
        
    }


	public function upload(Request $request){

	  
        $file = $request->file('file');
        $process_id = $request->id;

        $user_id = \Auth::user()->id;

        if(isset($file) && $file->isValid()){
            $fileName = $file->getClientOriginalName();
            $fileExtention = $file->getClientOriginalExtension();
            $fileRealPath = $file->getRealPath();
            $fileSize = $file->getSize();
            

            $extention = File::extension($fileName);

            $fileNameArray = explode('.',$fileName);

            $dir = 'uploads/process/'.$process_id.'/';

            if (!file_exists($dir) && !is_dir($dir)) {

                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $temp_file_name = trim($fileNameArray[0]);
            $fileNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
            $file->move($dir,$fileNewName);

            $fileNewPath = $dir.$fileNewName;

            $processFileUpload = new ProcessDoc();
            $processFileUpload->process_id = $process_id;
            $processFileUpload->file = $fileNewPath;
            $processFileUpload->size = $fileSize;
            $processFileUploadStored = $processFileUpload->save();
        }

        return redirect()->route('process.show',[$process_id])->with('success','Attachment uploaded successfully');
    }

    public function show($id){
		
		//$process_id = ProcessManual::find($id);

        $process_res = \DB::table('process_manual')
                    //->join('process_visible_users','process_visible_users.process_id','=','process_manual.id')
                    //->join('users','users.id','=','process_visible_users.user_id')
                    ->select('process_manual.*')
                    ->where('process_manual.id','=',$id)
                    ->get();

        $process = array();

        foreach ($process_res as $key => $value) {
            $process['id'] = $value->id;
            $process['title'] = $value->title;
            //$process['name'] = $value->name;

            $user = \Auth::user();
            $user_id = $user->id;
            $user_role_id = User::getLoggedinUserRole($user);

            $admin_role_id = env('ADMIN');
            $director_role_id = env('DIRECTOR');
            $manager_role_id = env('MANAGER');
            $superadmin_role_id = env('SUPERADMIN');

            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);

            if(in_array($user_role_id,$access_roles_id)){
                $process['access'] = '1';
            }
            else{
                if(isset($value->owner_id) && $value->owner_id==$user_id){
                    $process['access'] = '1';
                }
                else{
                    $process['access'] = '0';
                }
            }
        }

        $process_user_res = \DB::table('process_visible_users')
                            ->join('users','users.id','=','process_visible_users.user_id')
                            ->select('users.id', 'users.name as name')
                            ->where('process_visible_users.process_id',$id)
                            ->get();
        $c = 0;
        foreach ($process_user_res as $key => $value) {
            $process['name'][$c] = $value->name;
            $c++;
        }

		$i = 0;
        $processdetails['files'] = array();
        $processFiles = ProcessDoc::select('process_doc.*')
                ->where('process_doc.process_id',$id)
                ->get();

        $utils = new Utils();
            if(isset($processFiles) && sizeof($processFiles) > 0){
                foreach ($processFiles as $processFile) {
                $processdetails['files'][$i]['id'] = $processFile->id;
                $processdetails['files'][$i]['fileName'] = $processFile->file;
                $processdetails['files'][$i]['url'] = "../../".$processFile->file;
                $processdetails['files'][$i]['name'] = $processFile->name ;
                $processdetails['files'][$i]['size'] = $utils->formatSizeUnits($processFile->size);

                    $i++;

                }
            }
       
		// print_r($processdetails);die;
        return view('adminlte::process.show',compact('processdetails','process_id','process'));
    }

    
    public function processDestroy($id){
        ProcessDoc::where('process_id',$id)->delete();
        ProcessVisibleUser::where('process_id',$id)->delete();
        $process = ProcessManual::where('id',$id)->delete();

        return redirect()->route('process.index')->with('success','Process Deleted Successfully');
    }


    public function attachmentsDestroy($docid){

        $processDocDelete = ProcessDoc::where('id',$docid)->delete();

        $id = $_POST['id'];

        return redirect()->route('process.show',[$id])->with('success','Attachment deleted Successfully');
    } 

}
