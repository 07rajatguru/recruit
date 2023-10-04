<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\ClientHeirarchy;

class ClientHeirarchyController extends Controller
{
    public function index() {

    	$client_heirarchy = ClientHeirarchy::getAllClientHeirarchy();
        $count = sizeof($client_heirarchy);

    	return view('adminlte::clientheirarchy.index',compact('client_heirarchy','count'));
    }

    public function create() {

    	$action = 'add';
    	return view('adminlte::clientheirarchy.create',compact('action'));
    }

    public function store(Request $request) {

    	$name = $request->get('name');
    	$order = $request->get('order');
        $position = $request->get('position');

    	$client_heirarchy = new ClientHeirarchy();
    	$client_heirarchy->name = $name;
    	$client_heirarchy->order = $order;
        $client_heirarchy->position = $position;
    	$client_heirarchy->save();

    	return redirect()->route('clientheirarchy.index')->with('success','Client Heirarchy Added Successfully');
    }

    public function edit($id) {

        $id = Crypt::decrypt($id);

        $action = 'edit';
    	$client_heirarchy = ClientHeirarchy::find($id);

    	return view('adminlte::clientheirarchy.edit',compact('client_heirarchy','action'));
    }

    public function update($id,Request $request) {

    	$name = $request->get('name');
    	$order = $request->get('order');
        $position = $request->get('position');

    	$client_heirarchy = ClientHeirarchy::find($id);
    	$client_heirarchy->name = $name;
    	$client_heirarchy->order = $order;
        $client_heirarchy->position = $position;
    	$client_heirarchy->save();

    	return redirect()->route('clientheirarchy.index')->with('success','Client Heirarchy Updated Successfully');
    }

    public function destroy($id) {

    	ClientHeirarchy::where('id',$id)->delete();

    	return redirect()->route('clientheirarchy.index')->with('success','Client Herirarchy Deleted Successfully');
    }

    public function UpdatePosition() {

        $ids_array = explode(",", $_GET['ids']);

        $i = 1;
        foreach ($ids_array as $id) {

            $order = ClientHeirarchy::find($id);
            $order->order = $i;
            $order->save();
            $i++;
        }
    }
}