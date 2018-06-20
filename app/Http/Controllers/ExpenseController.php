<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\AccountingHeads;

class ExpenseController extends Controller
{
    public function index(){
    	
        return view('adminlte::expense.index');
    }

    public function create(){

        $payment_mode = Expense::getPaymentMode();
        $payment_type = Expense::getPaymentType();

        $head = AccountingHeads::getAllHead();

        //print_r($head);exit;

        $action = 'add';

        return view('adminlte::expense.create',compact('action','payment_mode','payment_type','head'));
    }

    public function store(Request $request){

        $input = $request->all();

        $date = $input['date'];
        $amount = $input['amount'];
        $paid_to = $input['paid_to'];
        $head = $input['head'];
        $remarks = $input['remarks'];
        $payment_mode = $input['pmode'];
        $payment_type = $input['ptype'];
        $number = $input['number'];

        $expense = new Expense();
        $expense->date = $date;
        $expense->amount = $amount;
        $expense->paid_to = $paid_to;
        $expense->expense_head = $head;
        $expense->remarks = $remarks;
        $expense->payment_mode = $payment_mode;
        $expense->type_of_payment = $payment_type;
        $expense->reference_number = $number;
        $expense->save();

        return redirect()->route('expense.index')->with('success', 'Expense Added Successfully');
    }

    public function edit(){

        $payment_mode = Expense::getPaymentMode();
        $payment_type = Expense::getPaymentType();

        $head = AccountingHeads::getAllHead();

        $action = 'edit';

        return view('adminlte::expense.edit',compact('action','payment_mode','payment_type','head'));
    }

    public function update(){

    }

    public function destory(){
    	
    }
}
