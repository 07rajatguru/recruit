<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies;

class CompaniesController extends Controller
{


    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $companies = Companies::orderBy('id','DESC')->get();
        return view('adminlte::companies.index',compact('companies'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $action = "add" ;
        return view('adminlte::companies.create',compact('action'));
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


        $company = new Companies();
        $company->name = $request->input('name');
        $company->description = $request->input('description');
        $company->save();

        return redirect()->route('companies.index')->with('success','Company created successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $companies = Companies::find($id);
        $action = 'edit';
        return view('adminlte::companies.edit',compact('companies','action'));
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

        $companies = Companies::find($id);
        $companies->name = $request->input('name');
        $companies->description = $request->input('description');
        $companies->save();

        return redirect()->route('companies.index')->with('success','Company updated successfully');

    }

}
