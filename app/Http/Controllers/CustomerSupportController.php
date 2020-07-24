<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerSupport;
use App\CustomerSupportDoc;
use App\Module;
use App\User;
use App\Events\NotificationMail;

class CustomerSupportController extends Controller
{
    public function index() {

    	$customer_support_res = CustomerSupport::getAllDetails();
    	$count = sizeof($customer_support_res);
        
    	return view('adminlte::customerSupport.index',compact('customer_support_res','count'));
    }

    public function create() {

        $modules = Module::getModules();
        $selected_modules = '';
        $action = 'add';
    	return view('adminlte::customerSupport.create',compact('action','modules','selected_modules'));
    }

    public function store(Request $request) {

    	$user_id = \Auth::user()->id;

    	$sub = $request->input('subject');;
    	$msg = $request->input('message');
    	$mod = $request->input('module');

    	$customer_support = new CustomerSupport();
    	$customer_support->user_id = $user_id;
    	$customer_support->subject = $sub;
    	$customer_support->module = $mod;
    	$customer_support->message = $msg;
    	$customer_support->save();

    	$upload_documents = $request->file('upload_documents');

    	$customer_support_id = $customer_support->id;

    	if (isset($upload_documents) && sizeof($upload_documents) > 0)
    	{
            foreach ($upload_documents as $k => $v)
            {
                if (isset($v) && $v->isValid())
                {
                    $file_name = $v->getClientOriginalName();
                    $file_extension = $v->getClientOriginalExtension();
                    $file_realpath = $v->getRealPath();
                    $file_size = $v->getSize();

                    $dir = 'uploads/customer_support/' . $customer_support_id . '/';

                    if (!file_exists($dir) && !is_dir($dir))
                    {
                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }

                    $v->move($dir, $file_name);

                    $file_path = $dir . $file_name;

                    $customer_support_doc = new CustomerSupportDoc();
                    $customer_support_doc->customer_support_id = $customer_support_id;
                    $customer_support_doc->file = $file_path;
                    $customer_support_doc->name = $file_name;
                    $customer_support_doc->size = $file_size;
                    $customer_support_doc->uploaded_by = $user_id;
                    $customer_support_doc->created_at = date('Y-m-d');
                    $customer_support_doc->updated_at = date('Y-m-d');
                    $customer_support_doc->save();

                }
            }
        }

        // get superadmin email id

        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        // get loggedin_user_email_id

        $loggedin_useremail = User::getUserEmailById($user_id);

        $cc_array = array($superadminemail,$loggedin_useremail);

        $module = "Customer Support";
        $sender_name = $user_id;
        $to = "saloni@trajinfotech.com";
        $cc = implode(",", $cc_array);

        $subject = "Customer Support - " . $sub;
        $message = "Customer Support - " . $msg;
        $module_id = $customer_support_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

    	return redirect()->route('customer.index')->with('success','Support Added Successfully');
    }

    public function show($id) {

    	$customer_support_res = CustomerSupport::getCustomerSupportDetailsById($id);
    	$customer_support_doc = CustomerSupportDoc::getCustomerSupportDocsById($id);

    	return view('adminlte::customerSupport.show',compact('customer_support_res','customer_support_doc'));
    }

    public function edit($id) {

    	$modules = Module::getModules();
        $action = 'edit';

        $customer_support = CustomerSupport::find($id);
        $selected_modules = $customer_support->module;

    	return view('adminlte::customerSupport.edit',compact('action','modules','selected_modules','customer_support'));
    }

    public function update(Request $request,$id) {

    	$sub = $request->input('subject');;
    	$msg = $request->input('message');
    	$mod = $request->input('module');

    	$customer_support = CustomerSupport::find($id);
    	$customer_support->subject = $sub;
    	$customer_support->module = $mod;
    	$customer_support->message = $msg;
    	$customer_support->save();

    	return redirect()->route('customer.index')->with('success','Support Updated Successfully');
    }

    public function destroy($id) {

    	$path = "uploads/customer_support/" . $id . "/";
    	$files = glob($path . "/*");

    	foreach ($files as $file_nm) {
    		unlink($file_nm);
    	}

    	if(is_dir($path)) {
    		rmdir($path);
    	}

    	CustomerSupportDoc::where('customer_support_id','=',$id)->delete();
    	CustomerSupport::where('id','=',$id)->delete();

    	return redirect()->route('customer.index')->with('success','Support Deleted Successfully');
    }

    public function upload(Request $request) {

    	$actiontype = $request->input('actiontype');
    	$user_id = \Auth::user()->id;
    	$upload_documents = $request->file('file');

    	$customer_support_id = $request->id;

    	if (isset($upload_documents) && sizeof($upload_documents) > 0)
    	{
            foreach ($upload_documents as $k => $v)
            {
                if (isset($v) && $v->isValid())
                {
                    $file_name = $v->getClientOriginalName();
                    $file_extension = $v->getClientOriginalExtension();
                    $file_realpath = $v->getRealPath();
                    $file_size = $v->getSize();

                    $dir = 'uploads/customer_support/' . $customer_support_id . '/';

                    if (!file_exists($dir) && !is_dir($dir))
                    {
                        mkdir($dir, 0777, true);
                        chmod($dir, 0777);
                    }

                    $v->move($dir, $file_name);

                    $file_path = $dir . $file_name;

                    $customer_support_doc = new CustomerSupportDoc();
                    $customer_support_doc->customer_support_id = $customer_support_id;
                    $customer_support_doc->file = $file_path;
                    $customer_support_doc->name = $file_name;
                    $customer_support_doc->size = $file_size;
                    $customer_support_doc->uploaded_by = $user_id;
                    $customer_support_doc->created_at = date('Y-m-d');
                    $customer_support_doc->updated_at = date('Y-m-d');
                    $customer_support_doc->save();

                }
            }
        }

        return redirect()->route('customer.show',[$customer_support_id])->with('success','Attachment Uploaded Successfully');
    }

    public function attachmentsDestroy($docid,Request $request) {

    	$customer_support_doc = \DB::table('customer_support_doc')
        ->select('customer_support_doc.*')->where('id','=',$docid)->first();

        if(isset($customer_support_doc)) {

        	$path = 'uploads/customer_support/' . $customer_support_doc->customer_support_id . '/' . $customer_support_doc->name;
        	unlink($path);
        }

    	$customer_support_id = $customer_support_doc->customer_support_id;
        CustomerSupportDoc::where('id',$docid)->delete();

        return redirect()->route('customer.show',[$customer_support_id])->with('success','Attachment Deleted Successfully');
    }
}