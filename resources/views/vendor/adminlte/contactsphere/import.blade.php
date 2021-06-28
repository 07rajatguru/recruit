@extends('adminlte::page')

@section('title', 'Contacts Import')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Import Contacts</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('contactsphere.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Import Excel</h3>
                </div><br/><br/>
                <form style="" action="{{ URL::to('contactsphere/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <input type="file" name="import_file"/>{{ csrf_field() }}<br/>
                    <button class="btn btn-primary">Import CSV or Excel File</button>
                </form>
                <br>
            </div>
        </div>
    </div>
@endsection