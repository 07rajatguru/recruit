<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClientHeirarchy;

class ClientHeirarchyController extends Controller
{
    public function index(){

    	$client_heirarchy = ClientHeirarchy::getAllClientHeirarchy();

    	return view('adminlte::clientheirarchy.index',compact('client_heirarchy'));
    }

    public function create(){

    	$action = 'add';

    	return view('adminlte::clientheirarchy.create',compact('action'));
    }

    public function store(Request $request){

    	$name = $request->get('name');
    	$order = $request->get('order');

    	$client_heirarchy = new ClientHeirarchy();
    	$client_heirarchy->name = $name;
    	$client_heirarchy->order = $order;
    	$client_heirarchy->save();

    	return redirect()->route('clientheirarchy.index')->with('success','Client Heirarchy Added Successfully');
    }

    public function edit($id){

    	$client_heirarchy = ClientHeirarchy::find($id);

    	$action = 'edit';

    	return view('adminlte::clientheirarchy.edit',compact('client_heirarchy','action'));

    }

    public function update($id,Request $request){

    	$name = $request->get('name');
    	$order = $request->get('order');

    	$client_heirarchy = ClientHeirarchy::find($id);
    	$client_heirarchy->name = $name;
    	$client_heirarchy->order = $order;
    	$client_heirarchy->save();

    	return redirect()->route('clientheirarchy.index')->with('success','Client Heirarchy Updated Successfully');
    }

    public function destroy($id){

    	$delete = ClientHeirarchy::where('id',$id)->delete();

    	return redirect()->route('clientheirarchy.index')->with('success','Client Herirarchy Deleted Successfully');
    }

    public function UpdatePosition(){

        $ids_array = $_GET['ids'];
        //$ids = $_POST['ids'];
        print_r($ids_array);
    }
}
