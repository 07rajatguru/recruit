<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmailTemplate;
use Illuminate\Support\Facades\Input;

class EmailTemplateController extends Controller
{
    public function index(){

    	$email_template = EmailTemplate::getAllEmailTemplates();
    	$count = sizeof($email_template);

    	return view('adminlte::emailtemplate.index',compact('email_template','count'));
    }

    public function create(){

    	$action = 'add';
    	return view('adminlte::emailtemplate.create',compact('action'));
    }

    public function uploadEmailbodyImage(Request $request){

        $user_id = \Auth::user()->id;

        $CKEditor = $request->input('CKEditor');
        $funcNum  = $request->input('CKEditorFuncNum');
        $message  = $url = '';

        if (Input::hasFile('upload'))
        {
            $file = Input::file('upload');
            if ($file->isValid())
            {
                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/uploads/users/'.$user_id.'/email_body/', $filename);
                $url = url('uploads/users/'.$user_id.'/email_body/' . $filename);
                $message = 'Image Upload Successfully.';
            }
            else
            {
                $message = 'An error occurred while uploading the file.';
            }
        }
        else
        {
            $message = 'No file uploaded.';
        }
        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
    }

    public function store(Request $request){

    	$name = $request->get('name');
    	$subject = $request->get('subject');
    	$email_body = $request->get('email_body');

    	$email_template = new EmailTemplate();
    	$email_template->name = $name;
    	$email_template->subject = $subject;
    	$email_template->email_body = $email_body;
    	$email_template->save();

    	return redirect()->route('emailtemplate.index')->with('success','Email Template Added Successfully.');
    }

    public function show($id){

    	$email_template = EmailTemplate::find($id);
    	return view('adminlte::emailtemplate.show',compact('email_template','action'));
    }

    public function edit($id){

    	$email_template = EmailTemplate::find($id);
    	$action = 'edit';

    	return view('adminlte::emailtemplate.edit',compact('email_template','action'));
    }

    public function update($id,Request $request){
    	
    	$name = $request->get('name');
    	$subject = $request->get('subject');
    	$email_body = $request->get('email_body');

    	$email_template = EmailTemplate::find($id);
    	$email_template->name = $name;
    	$email_template->subject = $subject;
    	$email_template->email_body = $email_body;
    	$email_template->save();

    	return redirect()->route('emailtemplate.index')->with('success','Email Template Updated Successfully.');
    }

    public function destroy($id){

    	EmailTemplate::where('id',$id)->delete();

    	return redirect()->route('emailtemplate.index')->with('success','Email Template Deleted Successfully.');
    }
}
