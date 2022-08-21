@extends('adminlte::page')

@section('title', 'Training Material')

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
                <h2>Training Material Details</h2>
            </div>

           <div class="pull-right">
                @if(isset($training_material['access']) && $training_material['access']==1)
                    <a class="btn btn-primary" href="{{route('training.edit',$training_material['id']) }}">Edit</a>
                @endif
                <a class="btn btn-primary" href="{{ route('training.index') }}">Back</a>
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
                            <th scope="row">Title</th>
                            <td>{{ $training_material['title'] }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Department</th>
                            <td>{{ $department_name }}</td>
                        </tr>
                        <tr>
                            <th>Who can see this Material</th>
                            <td>{{ implode(", ",$trainingdetails['name']) }}</td>
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
                    @if(isset($training_material['access']) && $training_material['access']==1)      
                        @include('adminlte::training.upload', ['name' => 'trainingattachments' , 'data' => $training_material])
                    @endif
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <th>File Name</th>
                            <th>Size</th>
                        </tr>

                        <tbody id="training_doc_table_tbody_id">
                            @if(isset($trainingdetails['files']) && sizeof($trainingdetails['files']) > 0)
                                @foreach($trainingdetails['files'] as $key => $value)
                                    <tr id="{{ $value['id'] }}">
                                        <td>
                                            {{--<a download href="{{ $value['url'] }}"><i class="fa fa-fw fa-download"></i></a>--}}
                                            &nbsp;
                                            @if(isset($training_material['access']) && $training_material['access']==1)     
                                                @include('adminlte::partials.confirm', ['data' => $value,'id'=>$training_material['id'], 'name' => 'trainingattachments' ,'display_name'=> 'Attachments','type' => 'Show'])
                                            @endif
                                        </td>

                                        <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a></td>
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
        jQuery("#training_doc_table_tbody_id").sortable({
            update: function (event, ui) {
                var order = $(this).sortable('toArray');
                var dataString = 'ids=' + order;
                $.ajax ({
                    type: "GET",
                    url: app_url+'/training/update-doc-position',
                    data: dataString,
                    cache: false,
                    success: function (data) {
                        if (data == 'success') {
                        }
                    }
                });
            }
        });
    });
</script>
@endsection