<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Expense;
use App\AccountingHeads;
use App\Date;
use App\User;
use App\ExpenseDoc;
use App\VendorBasicInfo;
use Excel;
use App\Utils;

class ExpenseController extends Controller
{
    public function index(){

        $user = \Auth::user();

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $expense = Expense::getAllExpense();
        //print_r($expense);exit;
    	
        $count=sizeof($expense);

        return view('adminlte::expense.index',compact('expense','isSuperAdmin','count'));
    }

    public function create(){

        $payment_mode = Expense::getPaymentMode();
        $payment_type = Expense::getPaymentType();

        $input_tax=Expense::getInputTax();

        $head = AccountingHeads::getAllHead();

        $pmode = '';
        $ptype = '';
        $tax='';
        $expense_head = '';
        $user = \Auth::user();
        $user_id = $user->id;

        $user_role_id = User::getLoggedinUserRole($user);
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            // get all vendor
            $vendor_res = VendorBasicInfo::getLoggedInUserVendors(1,$user_id);
        }
        else{
            // get logged in user vendor
            $vendor_res = VendorBasicInfo::getLoggedInUserVendors(0,$user_id);
        }

        $vendor = array();
        if (sizeof($vendor_res) > 0) {
            foreach ($vendor_res as $v) {
                $vendor[$v->id] = $v->name." - ".$v->address;
            }
        }

        $vendor_id=0;
        $action = 'add';

        return view('adminlte::expense.create',compact('action','payment_mode','payment_type','head','pmode','ptype','expense_head','input_tax','tax','vendor','vendor_id'));
    }

    public function store(Request $request){

        $dateClass = new Date();

     //   $document = $request->file('document');
/*        echo $document;
        exit;*/

        $input = $request->all();
        $date = $input['date'];
        $amount = $input['amount'];
        $head = $input['head'];
        $remarks = $input['remarks'];
        $payment_mode = $input['pmode'];
        $payment_type = $input['ptype'];
        $ref_number = $input['reference_number'];
        $vendor_id=$input['vendor_id'];
        $gst_no=$input['gst_no'];
        $pan_no=$input['pan_no'];
        $gst=$input['gst'];
        $cgst=$input['cgst'];
        $sgst=$input['sgst'];
        $igst=$input['igst'];
        $total_bill_amount=$input['bill_amount'];
        $input_tax=$input['tax'];
        $paid_amount=$input['paid_amount'];

        $tds=$input['tds'];
        if(isset($tds) && $tds!=null)
        {
            $tds=$input['tds'];
        }
        else
        {
            $tds='0.0';
        }

        $tds_detuct=$input['tds_deduct'];
        if(isset($tds_detuct) && $tds_detuct!=null)
        {
            $tds_deduct=$input['tds_deduct'];
        }
        else
        {
            $tds_detuct='0.0';
        }

        $tds_date=$input['tds_date'];

        if(isset($tds_date))
        {
            $tds_deduct=$input['tds_deduct'];
        }
        else
        {
            $tds_deduct="";
        }
        $expense = new Expense();
        $expense->date = $dateClass->changeDMYtoYMD($date);
        $expense->amount = $amount;
        $expense->paid_to=$vendor_id;
        $expense->expense_head = $head;
        $expense->remarks = $remarks;
        $expense->payment_mode = $payment_mode;
        $expense->type_of_payment = $payment_type;
        $expense->reference_number = $ref_number;
        $expense->vendor_id=$vendor_id;
        $expense->gst_in=$gst_no;
        $expense->pan_no=$pan_no;
        $expense->gst=$gst;

        $gst_split=substr($gst_no,0,2);
        if($gst_split=="24")
        {
             $expense->cgst=$cgst;
             $expense->sgst=$sgst;
             $expense->igst='0';
        }
        else
        {
             $expense->cgst='0';
             $expense->sgst='0';
             $expense->igst=$igst;
        }
       
        $expense->total_bill_amount=$total_bill_amount;
        $expense->input_tax=$input_tax;
        $expense->paid_amount=$paid_amount;
        $expense->tds_percentage=$tds;
        $expense->tds_deducted=$tds_detuct;
        if (isset($tds_date) && $tds_date != '') {
            $expense->tds_payment_date = $dateClass->changeDMYtoYMD($tds_date);
        }
        else {
            $expense->tds_payment_date = NULL;
        }

        $validator = \Validator::make(Input::all(),$expense::$rules);

        if($validator->fails()){
            return redirect('expense/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $expense_store =  $expense->save();

        $expense_id = $expense->id;

        $document1=$request->file('document1');
        $document2=$request->file('document2');
        $document3=$request->file('document3');

        if (isset($document1) && $document1->isValid()) 
        {
                    
            $file_name_doc1 = $document1->getClientOriginalName();
            $file_size_doc1 = fileSize($document1);

            //$extention = File::extension($file_name);
            $dir = "uploads/expense/" . $expense_id . '/';
            $expense_doc1_key = "uploads/expense/".$expense_id."/".$file_name_doc1;

            $file_path = $dir . $file_name_doc1;

            if (!file_exists($dir) && !is_dir($dir)) 
            {
                mkdir("uploads/expense/$expense_id", 0777, true);
                chmod($dir, 0777);
            }

            if(!$document1->move($dir, $file_name_doc1))
            {
                return false;
            }
            else
            {
                $expense_doc = new ExpenseDoc();
                $expense_doc->expence_id = $expense_id;
                $expense_doc->file = $file_path;
                $expense_doc->name = $file_name_doc1;
                $expense_doc->size = $file_size_doc1;
                $expense_doc->created_at = date('Y-m-d');
                $expense_doc->updated_at = date('Y-m-d');
                $expense_doc->save();
            }

        }

        if (isset($document2) && $document2->isValid()) 
        {
                    
            $file_name_doc2 = $document2->getClientOriginalName();
            $file_size_doc2 = fileSize($document2);

            //$extention = File::extension($file_name);
            $dir = "uploads/expense/" . $expense_id . '/';
            $expense_doc2_key = "uploads/expense/".$expense_id."/".$file_name_doc2;

            $file_path = $dir . $file_name_doc2;

            if (!file_exists($dir) && !is_dir($dir)) 
            {
                mkdir("uploads/expense/$expense_id", 0777, true);
                chmod($dir, 0777);
            }

            if(!$document2->move($dir, $file_name_doc2))
            {
                return false;
            }
            else
            {
                $expense_doc = new ExpenseDoc();
                $expense_doc->expence_id = $expense_id;
                $expense_doc->file = $file_path;
                $expense_doc->name = $file_name_doc2;
                $expense_doc->size = $file_size_doc2;
                $expense_doc->created_at = date('Y-m-d');
                $expense_doc->updated_at = date('Y-m-d');
                $expense_doc->save();
            }

        }

        if (isset($document3) && $document3->isValid()) 
        {
                    
            $file_name_doc3 = $document3->getClientOriginalName();
            $file_size_doc3 = fileSize($document3);

            //$extention = File::extension($file_name);
            $dir = "uploads/expense/" . $expense_id . '/';
            $expense_doc3_key = "uploads/expense/".$expense_id."/".$file_name_doc3;

            $file_path = $dir . $file_name_doc3;

            if (!file_exists($dir) && !is_dir($dir)) 
            {
                mkdir("uploads/expense/$expense_id", 0777, true);
                chmod($dir, 0777);
            }

            if(!$document3->move($dir, $file_name_doc3))
            {
                return false;
            }
            else
            {
                $expense_doc = new ExpenseDoc();
                $expense_doc->expence_id = $expense_id;
                $expense_doc->file = $file_path;
                $expense_doc->name = $file_name_doc3;
                $expense_doc->size = $file_size_doc3;
                $expense_doc->created_at = date('Y-m-d');
                $expense_doc->updated_at = date('Y-m-d');
                $expense_doc->save();
            }

        }
        return redirect()->route('expense.index')->with('success', 'Expense Added Successfully');
    }

    public function edit($id){

        $vendor_res = VendorBasicInfo::orderBy('id','ASC')->get();
        $vendor = array();

        if(sizeof($vendor_res)>0){
            foreach($vendor_res as $v){
                $vendor[$v->id]=$v->name;
            }
        }

        $dateClass = new Date();

        $payment_mode = Expense::getPaymentMode();
        $payment_type = Expense::getPaymentType();
        $input_tax=Expense::getInputTax();
        $head = AccountingHeads::getAllHead();

        $expense=array();

        $expense_info  = \DB::table('expense')
            
            ->leftjoin('vendor_basicinfo', 'vendor_basicinfo.id', '=', 'expense.vendor_id')
            ->select('expense.*','vendor_basicinfo.name as v_name')
            ->where('expense.id','=',$id)
            ->get();


         foreach ($expense_info as $key=>$value){
            $expense['date'] = $dateClass->changeYMDtoDMY($value->date);
            $expense['amount'] = $value->amount;
            $vendor_id=$value->vendor_id;
            $expense['name'] = $value->v_name;
            $expense_head = $value->expense_head;
            $expense['remark'] = $value->remarks;
            $pmode = $value->payment_mode;
            $ptype = $value->type_of_payment;
            $expense['reference_number'] = $value->reference_number;
            $expense['gstno'] = $value->gst_in;
            $expense['panno'] = $value->pan_no;
            $expense['gst'] = $value->gst;
            $expense['cgst'] = $value->cgst;
            $expense['sgst'] = $value->sgst;
            $expense['igst'] = $value->igst;
            $expense['total_bill'] = $value->total_bill_amount;
            $tax=$value->input_tax;
            $expense['paid_amount']=$value->paid_amount;
            $expense['tds']=$value->tds_percentage;
            $expense['tds_deduct']=$value->tds_deducted;
            $expense['tds_date']=$dateClass->changeYMDtoDMY($value->tds_payment_date);

        }

        $expense['id'] = $id;
        $expense = (object)$expense;
        $action = 'edit';

        return view('adminlte::expense.edit',compact('action','payment_mode','pmode','ptype','payment_type','expense_head','head','input_tax','tax','vendor','expense','vendor_id'));
    }

    public function update(Request $request,$id){

        $input = $request->all();
        $input = (object)$input;
        $dateClass = new Date();

        $expense = Expense::find($id);

        $expense->date = $dateClass->changeDMYtoYMD($input->date);

        $expense->paid_to=$input->vendor_id;
        $expense->vendor_id=$input->vendor_id;
        $expense->gst_in=$input->gst_no;
        $expense->pan_no=$input->pan_no;
        $expense->amount=$input->amount;

        $expense->gst=$input->gst;
        $gst_split=substr($expense->gst_in,0,2);
        if($gst_split=="24")
        {
             $expense->cgst=$input->cgst;
             $expense->sgst=$input->sgst;
             $expense->igst='0';
        }
        else
        {
             $expense->cgst='0';
             $expense->sgst='0';
             $expense->igst=$input->igst;
        }
     
        $expense->total_bill_amount=$input->bill_amount;
        $expense->input_tax=$input->tax;
        $expense->expense_head = $input->head;
        $expense->paid_amount=$input->paid_amount;

        $tds=$input->tds;
        if(isset($tds) && $tds!=null)
        {
            $expense->tds_percentage=$input->tds;
        }
        else
        {
            $expense->tds_percentage='0.0';
        }

        $tds_detuct=$input->tds_deduct;
        if(isset($tds_detuct) && $tds_detuct!=null)
        {
             $expense->tds_deducted=$input->tds_deduct;
        }
        else
        {
             $expense->tds_deducted='0.0';
        }

        $expense->tds_payment_date=$dateClass->changeDMYtoYMD($input->tds_date);

        $expense->remarks =$input->remarks;
        $expense->payment_mode = $input->pmode;
        $expense->type_of_payment = $input->ptype;
        $expense->reference_number = $input->reference_number;

        $validator = \Validator::make(Input::all(),$expense::$rules);

        if($validator->fails()){
            return redirect('expense/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $expenseUpdated =  $expense->save();

        return redirect()->route('expense.index')->with('success', 'Expense Updated Successfully');
    }

    public function destroy($id)
    {

        
        $expense_attach=\DB::table('expense_doc')->select('file','expence_id')->where('expence_id','=',$id)->first();

        if(isset($expense_attach))
        {
            $path="uploads/expense/".$expense_attach->expence_id;
 
            $files=glob($path . "/*");

            foreach($files as $file)
            {
                if(is_file($file))
                {
                    unlink($file);
                }
            }

            $expense_id=$expense_attach->expence_id;
            $path1="uploads/expense/". $expense_id . "/";
            rmdir($path1);
            $expense_doc=ExpenseDoc::where('expence_id','=',$id)->delete();
            $expense = Expense::where('id',$id)->delete();
        }
        else 
        {
            $expense = Expense::where('id',$id)->delete();
        }
     

        return redirect()->route('expense.index')->with('success','Expense Deleted Successfully');
    }


    public function getVendorInfo(){

        $vendor_id = $_GET['vendor_id'];

        // get vendor info
        $vendor = VendorBasicInfo::getVendorInfoByVendorId($vendor_id);

        echo json_encode($vendor);exit;

    }

    public function show($id)
    {   
        $dateClass = new Date();

        $expense = array();
        $expense_info  = \DB::table('expense')
            ->leftjoin('vendor_basicinfo', 'expense.vendor_id', '=', 'vendor_basicinfo.id')
            ->leftjoin('accounting_heads','accounting_heads.id','=','expense.expense_head')
            ->select('expense.*','vendor_basicinfo.name as v_name','accounting_heads.name as expense_head_name')
            ->where('expense.id','=',$id)
            ->get();


        $expense['id'] = $id;
       /* print_r( $expense_info);
        exit;*/

        foreach ($expense_info as $key=>$value){

            $expense['date'] = $dateClass->changeYMDtoDMY($value->date);
            $expense['amount'] = Utils::IND_money_format($value->amount);
            $expense['name'] = $value->v_name;
            $expense['head'] = $value->expense_head_name;
            $expense['remark'] = $value->remarks;
            $expense['pmode'] = $value->payment_mode;
            $expense['ptype'] = $value->type_of_payment;
            $expense['reference'] = $value->reference_number;
            $expense['gstno'] = $value->gst_in;
            $expense['panno'] = $value->pan_no;
            $expense['gst'] = $value->gst;
            $expense['cgst'] = $value->cgst;
            $expense['sgst'] = $value->sgst;
            $expense['igst'] = $value->igst;
            $expense['total_bill'] = Utils::IND_money_format($value->total_bill_amount);
            $expense['input_tax']=$value->input_tax;
            $expense['paidamt']=Utils::IND_money_format($value->paid_amount);
            $expense['tds']=$value->tds_percentage;
            $expense['tds_deduct']=$value->tds_deducted;
            $expense['tds_date']= $dateClass->changeYMDtoDMY($value->tds_payment_date);

        }

        $i=0;
        $expense['doc']=array();

        $expense_doc=\DB::table('expense_doc')
                    ->select('expense_doc.*')
                    ->where('expence_id','=',$id)
                    ->get();


        $utils = new Utils();

        foreach($expense_doc as $key=>$value)
        {
            $expense['doc'][$i]['name'] = $value->name ;
            $expense['doc'][$i]['id'] = $value->id;
            $expense['doc'][$i]['url'] = "../".$value->file;
            $expense['doc'][$i]['size'] = $utils->formatSizeUnits($value->size);
            $i++;
        }

        $expense_upload_type['Others'] = 'Others';
        return view('adminlte::expense.show',compact('expense','expense_upload_type'));
    }  

    public function upload(Request $request)
    {
        $expense_upload_type = $request->expense_upload_type;
        $file = $request->file('file');
        $id = $request->id;

        if (isset($file) && $file->isValid()) 
        {
            $doc_name = $file->getClientOriginalName();
            $doc_filesize = filesize($file);

            $dir_name = "uploads/expense/".$id."/";
            $others_doc_key = "uploads/expense/".$id."/".$doc_name;

            if (!file_exists($dir_name)) 
            {
                mkdir("uploads/expense/$id", 0777,true);
            }

            if(!$file->move($dir_name, $doc_name))
            {
                return false;
            }
            else
            {
                $expense_doc = new ExpenseDoc();
                $expense_doc->expence_id = $id;
                $expense_doc->file = $others_doc_key;
                $expense_doc->name = $doc_name;
                $expense_doc->size = $doc_filesize;
                $expense_doc->created_at = date('Y-m-d');
                $expense_doc->updated_at = date('Y-m-d');
                $expense_doc->save();
            }

        }

        return redirect()->route('expense.show',[$id])->with('success','Attachment Uploaded Successfully');

    }

    public function attachmentsDestroy($docid)
    {

        $expense_attach=\DB::table('expense_doc')
        ->select('expense_doc.*')
        ->where('id','=',$docid)->first();

        if(isset($expense_attach))
        {
            $path="uploads/expense/".$expense_attach->expence_id . "/" . $expense_attach->name;

            unlink($path);

            $id=$expense_attach->expence_id;
    
            $expense_doc=ExpenseDoc::where('id','=',$docid)->delete();
        }
        return redirect()->route('expense.show',[$id])->with('success','Attachment Deleted Successfully');
    }
    public function importExport()
    {
        return view('adminlte::expense.import');
    } 

    public function importExcel(Request $request)
    {
        $dateClass = new Date();

        if($request->hasFile('import_file')){
            $path = $request->file('import_file')->getRealPath();

            $data = Excel::load($path, function ($reader) {})->get();

            $messages = array();

            if(!empty($data) && $data->count()){
                foreach($data->toArray() as $key => $value){
                    if(!empty($value)){
                        //print_r($value);exit;
                        foreach($value as $v){

                            $sr_no = $v['sr_no'];
                            $date_of_expense = $v['date_of_expense'];
                            $paid_to = $v['paid_to'];
                            $gstin_of_client = $v['gstin_of_client_not_mandatory'];
                            $pan_number_for_tds = $v['pan_number_for_tds_not_mandatory'];
                            $billing_amount = $v['billing_amount_excluding_gst'];
                            $gst_charged = $v['gst_charged'];
                            $igst = $v['igst_at_18'];
                            $sgst = $v['sgst_at_9'];
                            $cgst = $v['cgst_at_9'];
                            $total_bill_amount = $v['total_bill_amount'];
                            $input_tax_credit = $v['input_tax_credit_yesno'];
                            $total_amount_paid = $v['total_amount_paid'];
                            $tds_percentage = $v['tds_percentage'];
                            $tds_deducted = $v['tds_deducted_if_any'];
                            $tds_payment_date = $v['tds_payment_date'];
                            $expense_head = $v['expense_head'];
                            $remarks = $v['remarks_if_any'];
                            $payment_mode = $v['payment_mode'];
                            $type_of_payment = $v['type_of_payment'];
                            $reference_number = $v['reference_number_not_mandatory'];

                            // Get VendorId from name
                            $vendor_id = VendorBasicInfo::getVendor($paid_to);
                            if ($vendor_id > 0) {
                                $expense_head_id = AccountingHeads::getHead($expense_head);
                                if ($expense_head_id > 0) {
                                    $expense = new Expense();
                                    $expense->date = $dateClass->changeDMYtoYMD($date_of_expense);
                                    $expense->amount = $billing_amount;
                                    $expense->paid_to = $vendor_id;
                                    $expense->expense_head = $expense_head_id;
                                    $expense->remarks = $remarks;
                                    $expense->payment_mode = $payment_mode;
                                    $expense->type_of_payment = $type_of_payment;
                                    $expense->reference_number = $reference_number;
                                    $expense->vendor_id = $vendor_id;
                                    $expense->gst_in = $gstin_of_client;
                                    $expense->pan_no = $pan_number_for_tds;
                                    $expense->gst = $gst_charged;
                                    $expense->igst = $igst;
                                    $expense->cgst = $cgst;
                                    $expense->sgst = $sgst;
                                    $expense->total_bill_amount = $total_bill_amount;
                                    $expense->input_tax = $input_tax_credit;
                                    $expense->paid_amount = $total_amount_paid;
                                    $expense->tds_percentage = $tds_percentage;
                                    $expense->tds_deducted = $tds_deducted;
                                    $expense->tds_payment_date = $tds_payment_date;
                                    if($expense->save()){
                                        $messages[] = "Record $sr_no inserted successfully";
                                    }
                                    else{
                                        $messages[] = "Error while inserting record $sr_no";  
                                    }
                                }
                                else {
                                    $messages[] = "$sr_no Expense Head not added.";
                                }
                            }
                            else {
                                $messages[] = "$sr_no vendor not added.";
                            }
                        }
                    }
                    else{
                        $messages[] = "No Data in file";
                    }
                } 
            }

            return view('adminlte::expense.import',compact('messages'));
        }
        else {
            return redirect()->route('expense.importExport')->with('error','Please Select Excel file.');
        }

    }
    
}
