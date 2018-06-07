@extends('adminlte::page')

@section('title', 'Process Detail')

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
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">
                <h2>Process Attachment</h2>
            </div>

           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('process.edit',$process_id) }}">Edit</a>
                <a class="btn btn-primary" href="{{ route('process.index') }}">Back</a>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th scope="row">Process Title</th>
                            <td>{{ $process['title'] }}</td>
                            <th>Who can show this process</th>
                            <td>{{ implode(",",$process['name']) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">    
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachments</h3>
                    &nbsp;&nbsp;
                    @include('adminlte::process.upload', ['name' => 'trainingattachments' , 'data' =>$process_id])
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <th>File Name</th>
                            <th>Size</th>
                            
                        </tr>

                        @if(isset($processdetails['files']) && sizeof($processdetails['files']) > 0)
                            @foreach($processdetails['files'] as $key => $value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}">
                                            <i class="fa fa-fw fa-download"></i>
                                        </a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=>$process_id['id'], 'name' => 'processattachments' ,'display_name'=> 'Attachments'])
                                          </td>

                                    <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['fileName'] }}</a></td>
                                    <td>{{ $value['size'] }}</td>
                                   </tr>
                            @endforeach
                        @endif
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection