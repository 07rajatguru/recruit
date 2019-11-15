<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClientRemarks;
use App\User;

class ClientRemarksController extends Controller
{
    public function index(){

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

    	$client_remarks = ClientRemarks::getAllClientRemarks();
    	$count = sizeof($client_remarks);

    	return view('adminlte::clientremarks.index',compact('client_remarks','count','isSuperAdmin'));
    }

    public function create(){

    	$action = 'add';
    	return view('adminlte::clientremarks.create',compact('action'));
    }

    public function store(Request $request){

    	$remarks = $request->get('remarks');

    	$client_remarks = new ClientRemarks();
    	$client_remarks->remarks = $remarks;
    	$client_remarks->save();

    	return redirect()->route('clientremarks.index')->with('success','Client Remarks Added Successfully.');
    }

    public function edit($id){

    	$client_remarks = ClientRemarks::find($id);
    	$action = 'edit';

    	return view('adminlte::clientremarks.edit',compact('client_remarks','action'));
    }

    public function update($id,Request $request){

    	$remarks = $request->get('remarks');

    	$client_remarks = ClientRemarks::find($id);
    	$client_remarks->remarks = $remarks;
    	$client_remarks->save();

    	return redirect()->route('clientremarks.index')->with('success','Client Remarks Updated Successfully.');
    }

    public function destroy($id){

    	ClientRemarks::where('id',$id)->delete();

    	return redirect()->route('clientremarks.index')->with('success','Client Remarks Deleted Successfully.');
    }

    public function searchRemarks(Request $request){

        $term = $request->get('term');
        $data = ClientRemarks::getSearchRemarks($term);
        return json_encode($data);
    }
}
