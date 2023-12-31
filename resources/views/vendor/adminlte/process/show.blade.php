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
                <h2>Process Manual Details</h2>
            </div>

           <div class="pull-right">
                @if(isset($process['access']) && $process['access']==1)
                    <a class="btn btn-primary" href="{{ route('process.edit',\Crypt::encrypt($process['id'])) }}">
                    Edit</a>
                @endif
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
                        </tr>
                        <tr>
                            <th scope="row">Department</th>
                            <td>{{ $department_name }}</td>
                        </tr>
                        <tr>
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
                    @if(isset($process['access']) && $process['access']==1)
                        @include('adminlte::process.upload', ['name' => 'processattachments' , 'data' =>$process])
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <th>File Name</th>
                            <th>Size</th>
                        </tr>

                        <tbody id="process_doc_table_tbody_id">
                            @if(isset($processdetails['files']) && sizeof($processdetails['files']) > 0)
                                @foreach($processdetails['files'] as $key => $value)
                                    <tr id="{{ $value['id'] }}">
                                        <td>
                                            {{--<a download href="{{ $value['url'] }}"><i class="fa fa-fw fa-download"></i></a>--}}
                                            &nbsp;
                                            @if(isset($process['access']) && $process['access']==1)
                                                @include('adminlte::partials.confirm', ['data' => $value,'id'=>$process['id'], 'name' => 'processattachments' ,'display_name'=> 'Attachments'])
                                            @endif
                                        </td>

                                        <td>
                                            <a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a>
                                        </td>
                                        <td>{{ $value['size'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('customscripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        var app_url = "{!! env('APP_URL'); !!}";
        jQuery("#process_doc_table_tbody_id").sortable( {
            update: function (event, ui) {
                var order = $(this).sortable('toArray');
                var dataString = 'ids=' + order;
                $.ajax({
                    
                    type: "GET",
                    url: app_url+'/process/update-doc-position',
                    data: dataString,
                    cache: false,
                    success: function (data)
                    {
                        if (data == 'success') {
                        }
                    }
                });
            }
        });
    });
</script>
@endsection