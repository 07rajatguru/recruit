<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Industry;
use DB;
use App\User;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {

        $industry = Industry::orderBy('id','DESC')->get();
        $count = sizeof($industry);

        return view('adminlte::industry.index',compact('industry','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create() {

        $action = "add" ;
        return view('adminlte::industry.create',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);

        $industry = new Industry();
        $industry->name = $request->input('name');
        $industry->save();

        return redirect()->route('industry.index')->with('success','Industry Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id) {

        $industry = Industry::find($id);
        return view('adminlte::industry.show',compact('industry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id) {

        $industry = Industry::find($id);
        $action = 'edit';
        return view('adminlte::industry.edit',compact('industry','action'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id) {

        $this->validate($request, [
            'name' => 'required'
        ]);

        $industry = Industry::find($id);
        $industry->name = $request->input('name');
        $industry->save();

        return redirect()->route('industry.index')->with('success','Industry Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id) {

        $client_industry = Industry::existIndustryInClient($id);

        if($client_industry) {

            return redirect()->route('industry.index')->with('error','Cannot delete Industry because use with client module.');
        }
        
        DB::table("industry")->where('id',$id)->delete();
        return redirect()->route('industry.index')->with('success','Industry Deleted Successfully.');
    }
}