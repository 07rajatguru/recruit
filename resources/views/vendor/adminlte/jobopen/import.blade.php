@extends('adminlte::page')

@section('title', 'Job Openings Detail')

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

    @if(isset($messages) && sizeof($messages)>0)
        <div class="alert alert-success">
            @foreach($messages as $key=>$value)
                <p>{{ $value }}</p>
            @endforeach
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Import Excel</h3>
                </div>

                <h3>Import File Form:</h3>
                <form style="" action="{{ URL::to('jobs/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">

                    <input type="file" name="import_file" />
                    {{ csrf_field() }}
                    <br/>

                    <button class="btn btn-primary">Import CSV or Excel File</button>

                </form>

                <br>
            </div>
        </div>
    </div>
@endsection