<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::all();

        return view('adminlte::documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('adminlte::documents.create');
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
            'file' => 'required'
        ]);

        $file = $request->file('file');

        if ($file->isValid()) {
            $name = $file->getClientOriginalName();
            $key = 'documents/' . $name;
            Storage::disk('s3')->put($key, file_get_contents($file));

            $document = new Document;

            $document->name = $name;
            $document->file = $key;
            $document->save();
        }

        return redirect('documents');
    }

}
