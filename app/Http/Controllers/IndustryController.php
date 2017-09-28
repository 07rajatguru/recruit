<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Industry;
use DB;

class IndustryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $industry = Industry::orderBy('id','DESC')->get();
        return view('adminlte::industry.index',compact('industry'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $action = "add" ;
        return view('adminlte::industry.create',compact('action'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
        ]);


        $industry = new Industry();
        $industry->name = $request->input('name');
        $industry->save();

        return redirect()->route('industry.index')->with('success','Industry created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $industry = Industry::find($id);

        return view('adminlte::industry.show',compact('industry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
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

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $industry = Industry::find($id);
        $industry->name = $request->input('name');
        $industry->save();

        return redirect()->route('industry.index')->with('success','Industry updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {

        DB::table("industry")->where('id',$id)->delete();
        return redirect()->route('industry.index')->with('success','Industry deleted successfully');

    }
}
