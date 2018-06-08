<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\pdfParser;
use App\User;
use App\Training;
use App\TrainingDoc;
use App\Utils;
use DB;


class TrainingController extends Controller
{
    public function index(){
	   
       $training = Training::All();

	   $trainingFiles = TrainingDoc::select('training_doc.file')->get();
	//print_r($trainingFiles);die;
	
    return view('adminlte::training.index',compact('training','trainingFiles'));
   
    }

    public function create(){

		$action = 'add';
        
        return view('adminlte::training.create',compact('action'));

    }
    

    public function store(Request $request){

    	
		$user_id = \Auth::user()->id;
        
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
        return redirect()->route('training.index')->with('success','Training Created Successfully');
    }

    public function edit($id){

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
		//print_r($trainingdetails);die;
        return view('adminlte::training.edit',compact('action','users','training','trainingdetails'));
    }


    public function update(Request $request,$id){

     	$user_id = \Auth::user()->id;
        
        $training = Training::find($id);
        $training->title = $request->input('title');
        $training->owner_id = $user_id;
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
        return redirect()->route('training.index')->with('success','Training Updated Successfully');
         
    }


    public function upload(Request $request){

	    
        $file = $request->file('file');
        $training_id = $request->id;

        $user_id = \Auth::user()->id;

        if(isset($file) && $file->isValid()){
            $fileName = $file->getClientOriginalName();
            $fileExtention = $file->getClientOriginalExtension();
            $fileRealPath = $file->getRealPath();
            $fileSize = $file->getSize();
            

            $extention = File::extension($fileName);

            $fileNameArray = explode('.',$fileName);

            $dir = 'uploads/training/'.$training_id.'/';

            if (!file_exists($dir) && !is_dir($dir)) {

                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
            $temp_file_name = trim($fileNameArray[0]);
            $fileNewName = $temp_file_name.date('ymdhhmmss').'.'.$extention;
            $file->move($dir,$fileNewName);

            $fileNewPath = $dir.$fileNewName;

            $trainingFileUpload = new TrainingDoc();
            $trainingFileUpload->training_id = $training_id;
            $trainingFileUpload->file = $fileNewPath;
            $trainingFileUpload->size = $fileSize;
            $trainingFileUploadStored = $trainingFileUpload->save();
        }

        return redirect()->route('training.show',[$training_id])->with('success','Attachment uploaded successfully');
    }

    
	public function show($id){
		
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
        return view('adminlte::training.show',compact('trainingdetails','training_id'));

    }
    
   public function trainingDestroy($id){
        TrainingDoc::where('training_id',$id)->delete();
        $training = Training::where('id',$id)->delete();

        return redirect()->route('training.index')->with('success','Training Deleted Successfully');
    }

    public function attachmentsDestroy($docid){

        $trainingDocDelete = TrainingDoc::where('id',$docid)->delete();

        $id = $_POST['id'];

        return redirect()->route('training.show',[$id])->with('success','Attachment deleted Successfully');
    } 


}
