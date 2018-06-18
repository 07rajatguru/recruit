<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\AccountingHeads;
use App\User;
use App\Utils;
use DB;

class AccountingController extends Controller

{
    public function index(){

    	$accountings = AccountingHeads::All();

    	return view('adminlte::accounting.index',compact('accountings'));
    }


    public function create(){

    	$action ='add';

    	return view('adminlte::accounting.create',compact('action'));
    }


    public function store(Request $request){

    	$user_id = \Auth::user()->id;
        
        $accounting = new AccountingHeads();
        
        $accounting->name = $request->input('name');
        $accountingStored  = $accounting->save();

        return redirect()->route('accounting.index')->with('success','Accounting Head Created Successfully');
    }

    public function edit($id){

    	$users = User::getAllUsers();
     	$accounting = AccountingHeads::find($id);
       
        $action = "edit" ;

        return view('adminlte::accounting.edit',compact('users','accounting','action'));
    }

     public function update(Request $request,$id){

     	$user_id = \Auth::user()->id;
        
        $accounting = AccountingHeads::find($id);
        $accounting->name = $request->input('name');
        $accountingStored  = $accounting->save();

       return redirect()->route('accounting.index')->with('success','Accounting Head Updated Successfully');

}

	public function Destroy($id){
        
        $accounting = AccountingHeads::where('id',$id)->delete();

        return redirect()->route('accounting.index')->with('success','Accounting Head Deleted Successfully');
    }

}
