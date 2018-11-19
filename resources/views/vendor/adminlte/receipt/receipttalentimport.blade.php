@extends('adminlte::page')

@section('title', 'Receipt Talent')

@section('content_header')

@stop

@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

@section('content')

@if(isset($messages) && sizeof($messages)>0)
    <div class="alert alert-success">
        @foreach($messages as $key=>$value)
                <p>{{ $value }}</p>
        @endforeach
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-error">
        <p>{{ $message }}</p>
    </div>
@endif

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Receipt Talent Import</h2>
        </div>

        <div class="pull-right">
        	<a class="btn btn-primary" href="{{ route('receipt.talent') }}"> Back</a>
        </div>
    </div>
</div>

{!! Form::open(['route' => 'receipt.talentimportstore', 'method' => 'POST', 'id'=>'recepit_talent_import', 'files' => true, 'enctype' => 'multipart/form-data']) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
           <div class="box-body col-xs-6 col-sm-6 col-md-6">
                <div class="">
                    <strong>Bank Type: <span class="required_fields"> *</span></strong>
                    {!! Form::select('bank_type', $bank_type,null, array('id'=>'bank_type','class' => 'form-control')) !!}
		            <br>
		            <input type="file" name="import_file" /><br/>
		            <button class="btn btn-primary">Import Excel File</button><br/>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('customscripts')
    <script type="text/javascript">
        $("#recepit_talent_import").validate({
            rules: {
                "bank_type": {
                    required: true
                },
            },
            messages: {
                "bank_type": {
                    required: "Bank Type is required."
                },
            }
        });
    </script>
@endsection