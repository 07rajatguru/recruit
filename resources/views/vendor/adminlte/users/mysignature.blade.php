@extends('adminlte::page')

@section('title', 'User Signature')

@section('content_header')
@stop

@section('content')

@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Signature</h2>  
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{url()->previous()}}">Back</a>
        </div>
    </div>
</div>

{!! Form::open(array('route' => ['users.signaturestore',$user_id],'method'=>'POST','files' => true,'autocomplete' => 'off')) !!}

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6"></div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-12 col-sm-12 col-md-12">
                    <div class="">
                        <div class="form-group">
                            <strong>Signature : </strong>
                            {!! Form::textarea('signature',isset($signature) ? $signature : null,array('id'=>'signature','placeholder' => 'Signature','class' => 'form-control','rows' => '4')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Update Signature</button>
        </div>
    </div>
</div>

{!! Form::close() !!}
@endsection

@section('customscripts')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script type="text/javascript">

        jQuery(document).ready(function () {

            CKEDITOR.replace( 'signature', {
                filebrowserUploadUrl: '{{ route('upload.signature',['_token' => csrf_token() ]) }}',
            });

            CKEDITOR.on('dialogDefinition', function( ev ) {
               var dialogName = ev.data.name;  
               var dialogDefinition = ev.data.definition;
                     
               switch (dialogName) {  
               case 'image': //Image Properties dialog      
               dialogDefinition.removeContents('Link');
               dialogDefinition.removeContents('advanced');
               break;      
               case 'link': //image Properties dialog          
               dialogDefinition.removeContents('advanced');   
               break;
               }
            });
        });
    </script>
@endsection