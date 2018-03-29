@extends('adminlte::page')

@section('title', 'Candidate Detail')

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

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">
                <h2>Import Excel</h2>
            </div>

           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('candidate.index') }}">Back</a>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <h3>Import File Form:</h3>
                <form style="" action="{{ URL::to('candidate/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">

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