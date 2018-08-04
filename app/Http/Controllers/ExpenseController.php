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
            // get all clients
            $vendor_res = VendorBasicInfo::getLoggedInUserVendors(0);
        }
        else{
            // get logged in user clients
            $vendor_res = VendorBasicInfo::getLoggedInUserVendors($user_id);
        }

        $vendor = array();

        if (sizeof($vendor_res) > 0) 
        {
            foreach ($vendor_res as $v) 
            {
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
        $expense->tds_payment_date = $dateClass->changeDMYtoYMD($tds_date);

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
            $expense['date'] = $value->date;
            $expense['amount'] = $value->amount;
            $vendor_id=$value->vendor_id;
            $expense['name'] = $value->v_name;
            $expense_head = $value->expense_head;
            $expense['remark'] = $value->remarks;
            $pmode = $value->payment_mode;
            $ptype = $value->type_of_payment;
            $expense['reference'] = $value->reference_number;
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
            $expense['tds_date']=$value->tds_payment_date;  

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

    public function destroy($id){

        $expense_doc=ExpenseDoc::where('expence_id','=',$id)->delete();
        $expense = Expense::where('id',$id)->delete();

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
        $expense = array();
        $expense_info  = \DB::table('expense')
            ->leftjoin('vendor_basicinfo', 'expense.vendor_id', '=', 'vendor_basicinfo.id')
            ->select('expense.*','vendor_basicinfo.name as v_name')
            ->where('expense.id','=',$id)
            ->get();


        $expense['id'] = $id;
       /* print_r( $expense_info);
        exit;*/

        foreach ($expense_info as $key=>$value){

            $expense['date'] = $value->date;
            $expense['amount'] = $value->amount;
            $expense['name'] = $value->v_name;
            $expense['head'] = $value->expense_head;
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
            $expense['total_bill'] = $value->total_bill_amount;
            $expense['input_tax']=$value->input_tax;
            $expense['paidamt']=$value->paid_amount;
            $expense['tds']=$value->tds_percentage;
            $expense['tds_deduct']=$value->tds_deducted;
            $expense['tds_date']=$value->tds_payment_date;      

        }
        return view('adminlte::expense.show',compact('expense'));
    }  

    public function importExport()
    {
        return view('adminlte::expense.import');
    } 

    public function importExcel(Request $request)
    {

        if($request->hasFile('import_file'))
        {
            $path = $request->file('import_file')->getRealPath();

            $data = Excel::load($path, function ($reader) {})->get();

            $messages = array();

            if(!empty($data) && $data->count())
            {
                foreach($data->toArray() as $key => $value)
                {
                    if(!empty($value))
                    {
                        foreach($value as $v)
                        {

                            $srno=$v['srno'];
                            $date=$v['date'];
                            $amount=$v['amount'];
                            $paidto=$v['paidto'];
                            $expensehead=$v['expensehead'];
                            $remark=$v['remark'];
                            $paymode=$v['paymode'];
                            $paytype=$v['paytype'];
                            $refno=$v['refno'];
                            $vendor=$v['vendor'];
                            $gstno=$v['gstno'];
                            $panno=$v['panno'];
                            $gst=$v['gst'];
                            $igst=$v['igst'];
                            $cgst=$v['cgst'];
                            $sgst=$v['sgst'];
                            $billamount=$v['billamount'];
                            $paidamount=$v['paidamount'];
                            $inputtax=$v['inputtax'];
                            $tds=$v['tds'];
                            $tdsdeduct=$v['tdsdeduct'];
                            $tdsdate=$v['tdsdate'];

                            
                            $expense=new Expense();
                            $expense->date=$date;
                            $expense->amount=$amount;
                            

                            $head_id=AccountingHeads::getHead($expensehead);
                            $expense->expense_head=$head_id;
                            $expense->remarks=$remark;
                            $expense->payment_mode=$paymode;
                            $expense->type_of_payment=$paytype;
                            $expense->reference_number=$refno;

                            $vendor_id=VendorBasicInfo::getVendor($vendor);
                            $expense->vendor_id=$vendor_id;
                            $expense->paid_to=$vendor_id;

                            $expense->gst_in=$gstno;
                            $expense->pan_no=$panno;
                            $expense->gst=$gst;
                            $expense->igst=$igst;
                            $expense->cgst=$cgst;
                            $expense->sgst=$sgst;
                            $expense->total_bill_amount=$billamount;
                            $expense->paid_amount=$paidamount;
                            $expense->input_tax=$inputtax;
                            $expense->tds_percentage=$tds;
                            $expense->tds_deducted=$tdsdeduct;
                            $expense->tds_payment_date=$tdsdate;

                            if($expense->save())
                            {
                                    $messages[] = "Record $srno inserted successfully";
                            }
                            else
                            {
                                    $messages[] = "Error while inserting record $srno";  
                            }

                        }
                    }
                    else
                    {
                        $messages[] = "No Data in file";
                    }
                }
                    
            }

             return view('adminlte::expense.import',compact('messages'));
        }

    }
    
}
