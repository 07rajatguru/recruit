<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\pdfParser;
use App\User;
use App\Training;
use App\TrainingDoc;
use App\TrainingVisibleUser;
use App\Utils;
use DB;
use App\Events\NotificationMail;

class TrainingController extends Controller
{
    public function index(){

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

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){

            $training = Training::getAlltraining(1,$user_id);
	        $count = Training::getAlltrainingCount(1,$user_id);
        }
        else{

            $training = Training::getAlltraining(0,$user_id);
            $count = Training::getAlltrainingCount(0,$user_id);   
        }

	   //$trainingFiles = TrainingDoc::select('training_doc.file')->get();
	   //print_r($trainingFiles);die;
	
    return view('adminlte::training.index',compact('training','trainingFiles','isSuperAdmin','user_id','count'));
   
    }

    public function getOrderTrainingColumnName($order){

        $order_column_name = '';
        if (isset($order) && $order >= 0) {
            if ($order == 0) {
                $order_column_name = "training.id";
            }
            else if ($order == 1) {
                $order_column_name = "training.title";
            }
        }

        return $order_column_name;
    }

    public function getAllTrainingDetails(){

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $delete_perm = $user->can('training-destroy');
        $edit_perm = $user->can('training-edit');

        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $order_column_name = self::getOrderTrainingColumnName($order);
            $training = Training::getAlltraining(1,$user_id,$limit,$offset,$search,$order_column_name,$type);
            $count = Training::getAlltrainingCount(1,$user_id,$search);
        }
        else{
            $order_column_name = self::getOrderTrainingColumnName($order);
            $training = Training::getAlltraining(0,$user_id,$limit,$offset,$search,$order_column_name,$type);
            $count = Training::getAlltrainingCount(0,$user_id,$search);
        }

        $trainingFiles = TrainingDoc::select('training_doc.file')->get();

        $training_data = array();
        $i = 0; $j = 0;
        foreach ($training as $key => $value) {
            $action = '';
            $action .= '<a title="Show" class="fa fa-circle" href="'.route('training.show',$value['id']).'" style="margin:2px;"></a>';
            if($value['owner_id'] == $user_id || $isSuperAdmin || $edit_perm){
                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('training.edit',$value['id']).'" style="margin:2px;"></a>';
            }
            /*if ($isSuperAdmin || $value['owner_id'] == $user_id || $delete_perm) {*/
            if ($isSuperAdmin) {
                $delete_view = \View::make('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'training','display_name'=>'Training']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            $doc_count = TrainingDoc::getTrainingDocCount($value['id']);
            if ($doc_count == 1) {
                $title = '<a target="_blank" href="'.$value['file_url'].'">'.$value['title'].'</a>';
            }
            else {
                $title = $value['title'];
            }
            $data = array(++$j,$title,$action);
            $training_data[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $training_data
        );

        echo json_encode($json_data);exit;
    }

    public function create(){

		$action = 'add';

        $user = \Auth::user();
        $user_id = $user->id;

        $users_recruit = User::getAllUsers('recruiter');
        $users_hr = User::getAllUsers('hr');

        $users = $users_recruit + $users_hr;
        
        $super_admin_user_id = getenv('SUPERADMINUSERID');
        $selected_users = array($user_id,$super_admin_user_id);
        
        return view('adminlte::training.create',compact('action','users','selected_users','user_id'));

    }
    

    public function store(Request $request){

    	
		$user_id = \Auth::user()->id;
        $title = $request->input('title');
        
        $training = new Training();
        $training->title = $request->input('title');
        $training->owner_id = $user_id;
        $trainingStored  = $training->save();

        $upload_documents = $request->file('upload_documents');
        //print_r($upload_documents);exit;

        //save files 
        $training_id = $training->id;

        if (isset($upload_documents) && sizeof($upload_documents) > 0) {
            foreach ($upload_documents as $k => $v) {
                if (isset($v) && $v->isValid()) {
                    // echo "here";
                    $file_name = $v->getClientOriginalName();
                    $file_extension = $v->getClientOriginalExtension();
                    $file_realpath = $v->getRealPath();
                    $file_size = $v->getSize();

                    //$extention = File::extension($file_name);

                    $dir = 'uploads/training/' . $training_id . '/';

                    if (!file_exists($dir) && !is_dir($dir)) {
                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }
                    $v->move($dir, $file_name);

                    $file_path = $dir . $file_name;

                    $training_doc = new TrainingDoc();
                    $training_doc->training_id = $training_id;
                    $training_doc->file = $file_path;
                    $training_doc->name = $file_name;
                    $training_doc->size = $file_size;
                    $training_doc->created_at = date('Y-m-d');
                    $training_doc->updated_at = date('Y-m-d');		
                    $training_doc->save();
                }
            }
        }  

        $users = $request->input('user_ids');
        if (isset($users) && sizeof($users)>0) {
            foreach ($users as $key => $value) {
                $training_visible_users = new TrainingVisibleUser;
                $training_visible_users->training_id = $training_id;
                $training_visible_users->user_id = $value;
                $training_visible_users->save();
            }
        }

        // Check training material for all users or not and update select_all field in daatabase
        $users_id = User::getAllUsers('recruiter');
        $user_count = sizeof($users_id);

        $training_users = sizeof($users);
        if ($training_users == $user_count) {
            \DB::statement("UPDATE training SET select_all = '1' where id=$training_id");
        }
        else {
            \DB::statement("UPDATE training SET select_all = '0' where id=$training_id");
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

        $module = "Training Material";
        $sender_name = $user_id;
        $to = implode(",",$user_emails);
        $cc = $superadminemail;

        $subject = "Training Material - ". $title;
        $message = "Training Material - ". $title;
        $module_id = $training_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('training.index')->with('success','Training Created Successfully');
    }

    public function edit($id){

     	/*$users_recruit = User::getAllUsers('recruiter');
        $users_hr = User::getAllUsers('hr');

        $users = $users_recruit + $users_hr;*/

        $users = User::getAllUsers();

     	$training = Training::find($id);
        
        $action = "edit" ;

        $i = 0;
        $trainingdetails['files'] = array();
        $trainingFiles = TrainingDoc::select('training_doc.*')
                ->where('training_doc.training_id',$id)
                ->get();

        $utils = new Utils();
            if(isset($trainingFiles) && sizeof($trainingFiles) > 0){
                foreach ($trainingFiles as $trainingfile) {
                    $trainingdetails['files'][$i]['id'] = $trainingfile->id;
                    $trainingdetails['files'][$i]['fileName'] = $trainingfile->file;
                    $trainingdetails['files'][$i]['url'] = "../../".$trainingfile->file;
                    $trainingdetails['files'][$i]['name'] = $trainingfile->name ;
                    $trainingdetails['files'][$i]['size'] = $utils->formatSizeUnits($trainingfile->size);

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
        
        $training_visible_users =TrainingVisibleUser::where('training_id',$id)->get();
       // echo'<pre>'; print_r($training_visible_users);die;
       
        $selected_users = array();
        if(isset($training_visible_users) && sizeof($training_visible_users)>0){
            foreach($training_visible_users as $row){
                $selected_users[] = $row->user_id;
            }
        }
		//print_r($trainingdetails);die;
        return view('adminlte::training.edit',compact('action','users','training','trainingdetails','selected_users'));
    }


    public function update(Request $request,$id){

     	$user_id = \Auth::user()->id;
        
        $training = Training::find($id);
        $owner_id = $training->owner_id;

        $training->title = $request->input('title');
        $training->owner_id = $owner_id;
        $trainingStored  = $training->save();

        $file = $request->file('file');

        //save files 
        $training_id = $training->id;         
        if (isset($file) && sizeof($file) > 0) {
            //print_r($upload_documents);exit;
            if (isset($file) && $file->isValid()) {
                // echo "here";
                $file_name = $file->getClientOriginalName();
                $file_extension = $file->getClientOriginalExtension();
                $file_realpath = $file->getRealPath();
                $file_size = $file->getSize();

                //$extention = File::extension($file_name);

                $dir = 'uploads/training/' . $training_id . '/';

                if (!file_exists($dir) && !is_dir($dir)) {
                    mkdir($dir, 0777, true);
                    chmod($dir, 0777);
                }
                $file->move($dir, $file_name);

                $file_path = $dir . $file_name;
                $training_doc = new TrainingDoc();
                $training_doc->training_id = $training_id;
                $training_doc->file = $file_path;
                $training_doc->name = $file_name;
                $training_doc->size = $file_size;
                $training_doc->created_at = date('Y-m-d');
                $training_doc->updated_at = date('Y-m-d');
			    $training_doc->save();
            }

            return redirect('training/'. $id .'/edit');        
        }

        $users = $request->input('user_ids');
        TrainingVisibleUser::where('training_id',$training_id)->delete();
        if (isset($users) && sizeof($users)>0) {
            foreach ($users as $key => $value) {
                $training_visible_users = new TrainingVisibleUser;
                $training_visible_users->training_id = $training_id;
                $training_visible_users->user_id = $value;
                $training_visible_users->save();
            }
        }

        // Check training material for all users or not and update select_all field in daatabase
        $users_id = User::getAllUsers('recruiter');
        $user_count = sizeof($users_id);

        $training_users = sizeof($users);
        if ($training_users == $user_count) {
            \DB::statement("UPDATE training SET select_all = '1' where id=$training_id");
        }
        else {
            \DB::statement("UPDATE training SET select_all = '0' where id=$training_id");
        }

        return redirect()->route('training.index')->with('success','Training Updated Successfully');
         
    }

    public function upload(Request $request){
	    
        $file = $request->file('file');
        $training_id = $request->id;

        $user_id = \Auth::user()->id;

        if(isset($file) && $file->isValid())
        {
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            
            /*$fileExtention = $file->getClientOriginalExtension();
            $fileRealPath = $file->getRealPath();
            $extention = File::extension($fileName);
            $fileNameArray = explode('.',$fileName);*/

            $dir = 'uploads/training/'.$training_id.'/';

            if (!file_exists($dir) && !is_dir($dir)) {

                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            
            $file->move($dir,$fileName);
            $filePath = $dir . $fileName;

            $trainingFileUpload = new TrainingDoc();
            $trainingFileUpload->training_id = $training_id;
            $trainingFileUpload->file = $filePath;
            $trainingFileUpload->name = $fileName;
            $trainingFileUpload->size = $fileSize;
            $trainingFileUpload->save();
        }

        return redirect()->route('training.show',[$training_id])->with('success','Attachment uploaded successfully');
    }

    
	public function show($id){

        $user = \Auth::user();
        $user_id = $user->id;
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();

        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
		
		$training_id = Training::find($id);
			
        	$trainingModel = new Training();
            $trainingdetails['id'] = $trainingModel->id;           

		$i = 0;
        $trainingdetails['files'] = array();
        $trainingFiles = TrainingDoc::select('training_doc.*')
                ->where('training_doc.training_id',$id)
                ->get();

        $utils = new Utils();
            if(isset($trainingFiles) && sizeof($trainingFiles) > 0){
                foreach ($trainingFiles as $trainingfile) {
                    $trainingdetails['files'][$i]['id'] = $trainingfile->id;
                    $trainingdetails['files'][$i]['fileName'] = $trainingfile->file;
                    $trainingdetails['files'][$i]['url'] = "../../".$trainingfile->file;
                    $trainingdetails['files'][$i]['name'] = $trainingfile->name ;
                    $trainingdetails['files'][$i]['size'] = $utils->formatSizeUnits($trainingfile->size);

                    $i++;

                }
            }
       
		// print_r($trainingdetails);die;
        return view('adminlte::training.show',compact('trainingdetails','training_id','user_id','isSuperAdmin'));

    }
    
   public function trainingDestroy($id){
        TrainingDoc::where('training_id',$id)->delete();
        TrainingVisibleUser::where('training_id',$id)->delete();
        $training = Training::where('id',$id)->delete();

        return redirect()->route('training.index')->with('success','Training Deleted Successfully');
    }

    public function attachmentsDestroy($docid){

        $training_attch = \DB::table('training_doc')
        ->select('training_doc.*')
        ->where('id','=',$docid)->first();

        if(isset($training_attch))
        {
            $path = "uploads/training/".$training_attch->training_id ."/". $training_attch->name;
            unlink($path);

            $trainingDocDelete = TrainingDoc::where('id',$docid)->delete();
        }

        $id = $_POST['id'];

        return redirect()->route('training.show',[$id])->with('success','Attachment deleted Successfully');
    }

    public function UpdatePosition(){

        $ids_array = explode(",", $_GET['ids']);

        $i = 1;
        foreach ($ids_array as $id) {

            $order = Training::find($id);
            $order->position = $i;
            $order->save();
            $i++;
        }
    }
}
