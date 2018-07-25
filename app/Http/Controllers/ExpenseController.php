<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Expense;
use App\AccountingHeads;
use App\Date;
use App\User;
use App\ExpenseDoc;
use App\ClientBasicinfo;

class ExpenseController extends Controller
{
    public function index(){

        $user = \Auth::user();

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $expense = Expense::getAllExpense();
        //print_r($expense);exit;
    	
        return view('adminlte::expense.index',compact('expense','isSuperAdmin'));
    }

    public function create(){

        $payment_mode = Expense::getPaymentMode();
        $payment_type = Expense::getPaymentType();

        $head = AccountingHeads::getAllHead();

        $pmode = '';
        $ptype = '';
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
            $client_res = ClientBasicinfo::getLoggedInUserClients(0);
        }
        else{
            // get logged in user clients
            $client_res = ClientBasicinfo::getLoggedInUserClients($user_id);
        }

        $client = array();

        if (sizeof($client_res) > 0) {
            foreach ($client_res as $r) {
                $client[$r->id] = $r->name." - ".$r->coordinator_name." - ".$r->billing_city;
            }
        }

        // get all users
       
        //print_r($users);exit;
      //  $team_mates = $user_id;

        // job opening status
        /*$job_open_status = JobOpen::getJobOpenStatus();*/

        // job type
       // $job_type = Expense::getJobTypes();

        // job priority
       // $job_priorities = JobOpen::getJobPriorities();


        //print_r($head);exit;

        $action = 'add';

        return view('adminlte::expense.create',compact('action','payment_mode','payment_type','head','pmode','ptype','expense_head','client'));
    }

    public function store(Request $request){

        $dateClass = new Date();

        $document = $request->file('document');
        echo $document;exit;

        $input = $request->all();

        $date = $input['date'];
        $amount = $input['amount'];
        $paid_to = $input['paid_to'];
        $head = $input['head'];
        $remarks = $input['remarks'];
        $payment_mode = $input['pmode'];
        $payment_type = $input['ptype'];
        $number = $input['reference_number'];

        $expense = new Expense();
        $expense->date = $dateClass->changeDMYtoYMD($date);
        $expense->amount = $amount;
        $expense->paid_to = $paid_to;
        $expense->expense_head = $head;
        $expense->remarks = $remarks;
        $expense->payment_mode = $payment_mode;
        $expense->type_of_payment = $payment_type;
        $expense->reference_number = $number;

        $validator = \Validator::make(Input::all(),$expense::$rules);

        if($validator->fails()){
            return redirect('expense/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $expense_store =  $expense->save();

        $expense_id = $expense->id;

            if (isset($document) && sizeof($document) > 0) {
                foreach ($document as $k => $v) {
                    if (isset($v) && $v->isValid()) {
                        // echo "here";
                        $file_name = $v->getClientOriginalName();
                        $file_extension = $v->getClientOriginalExtension();
                        $file_realpath = $v->getRealPath();
                        $file_size = $v->getSize();

                        //$extention = File::extension($file_name);

                        $dir = 'uploads/expense/' . $expense_id . '/';

                        if (!file_exists($dir) && !is_dir($dir)) {
                            mkdir($dir, 0777, true);
                            chmod($dir, 0777);
                        }
                        $v->move($dir, $file_name);

                        $file_path = $dir . $file_name;

                        $expense_doc = new ExpenseDoc();
                        $expense_doc->expense_id = $expense_id;
                        $expense_doc->file = $file_path;
                        $expense_doc->name = $file_name;
                        $expense_doc->size = $file_size;
                        $expense_doc->created_at = date('Y-m-d');
                        $expense_doc->updated_at = date('Y-m-d');

                        $expense_doc->save();
                    }

                }
            }

        return redirect()->route('expense.index')->with('success', 'Expense Added Successfully');
    }

    public function edit($id){

        $dateClass = new Date();

        $payment_mode = Expense::getPaymentMode();
        $payment_type = Expense::getPaymentType();

        $head = AccountingHeads::getAllHead();

        $expense = Expense::find($id);
        $pmode = $expense->payment_mode;
        $ptype = $expense->type_of_payment;
        $expense_head = $expense->expense_head;

        $action = 'edit';

        return view('adminlte::expense.edit',compact('action','payment_mode','payment_type','head','expense','pmode','ptype','expense_head'));
    }

    public function update(Request $request,$id){

        $dateClass = new Date();

        $input = $request->all();

        $date = $input['date'];
        $amount = $input['amount'];
        $paid_to = $input['paid_to'];
        $head = $input['head'];
        $remarks = $input['remarks'];
        $payment_mode = $input['pmode'];
        $payment_type = $input['ptype'];
        $number = $input['reference_number'];

        $expense = Expense::find($id);
        $expense->date = $dateClass->changeDMYtoYMD($date);
        $expense->amount = $amount;
        $expense->paid_to = $paid_to;
        $expense->expense_head = $head;
        $expense->remarks = $remarks;
        $expense->payment_mode = $payment_mode;
        $expense->type_of_payment = $payment_type;
        $expense->reference_number = $number;

        $validator = \Validator::make(Input::all(),$expense::$rules);

        if($validator->fails()){
            return redirect('expense/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $expenseUpdated =  $expense->save();

        return redirect()->route('expense.index')->with('success', 'Expense Updated Successfully');
    }

    public function destroy($id){

        $expense_delete = Expense::where('id',$id)->delete();

        return redirect()->route('expense.index')->with('success', 'Expense Deleted Successfully');    	
    }
}
