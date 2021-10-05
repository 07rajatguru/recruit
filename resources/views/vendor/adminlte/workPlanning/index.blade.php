@extends('adminlte::page')

@section('title', 'Work Planning')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Work Planning Sheet</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('workplanning.create') }}">Add Work Planning</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-error">
        <p>{{ $message }}</p>
    </div>
@endif

<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="box-body col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            {{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control'))}}
        </div>
    </div>

    <div class="box-body col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
        </div>
    </div>

    <div class="box-body col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
        </div>
    </div> 
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2" style="width: 15%;">
            <a href="{{ route('workplanning.status',array('pending',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#8FB1D5;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Pending">Pending ({{ $pending }})</div></a>
        </div>

        <div class="col-md-2" style="width: 15%;">
            <a href="{{ route('workplanning.status',array('approved',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#32CD32;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Approved">Approved ({{ $approved }})</div></a>
        </div>

        <div class="col-md-2" style="width: 15%;">
            <a href="{{ route('workplanning.status',array('rejected',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#F08080;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Rejected">Rejected ({{ $rejected }})</div></a>
        </div>
    </div>
</div><br/>

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="work_planning_table">
    <thead>
        <tr>
	       <th width="5%">No</th>
           <th width="8%">Action</th>
           <th>Date</th>
           <th>Username</th>
           <th>Work Location</th>
           <th>Logged-in Time</th>
	       <th>Logged-out Time</th>
           <th>Work Planning Time</th>
           <th>Status Time</th>
	    </tr>
    </thead>
    <tbody>
        <?php $i=0; ?>
        @foreach ($work_planning_res as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="fa fa-circle" href="{{ route('workplanning.show',$value['id']) }}" title="Show"></a>

                    <a class="fa fa-edit" href="{{ route('workplanning.edit',$value['id']) }}" title="Edit"></a>
                    
                    @permission(('work-planning-delete'))
                        @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'workplanning','display_name'=>'Work Planning'])
                    @endpermission

                    @include('adminlte::partials.sendWorkPlanningReport', ['data' => $value, 'name' => 'workplanning'])
                </td>

                @if($value['status'] == 0)
                    <td style="background-color:#8FB1D5;">{{ $value['added_date'] }}</td>
                @elseif($value['status'] == 1)
                    <td style="background-color:#32CD32;">{{ $value['added_date'] }}</td>
                @else
                    <td style="background-color:#F08080;">{{ $value['added_date'] }}</td>
                @endif
 
                <td>{{ $value['added_by'] }}</td>
                <td>{{ $value['work_type'] }}</td>
                <td>{{ $value['loggedin_time'] }}</td>
                <td>{{ $value['loggedout_time'] }}</td>
                <td>{{ $value['work_planning_time'] }}</td>
                <td>{{ $value['work_planning_status_time'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var table = jQuery('#work_planning_table').DataTable({
                responsive: true,
                stateSave : true,
                "order" : [2,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });

        function select_data() {

            var app_url = "{!! env('APP_URL'); !!}";
            var month = $("#month").val();
            var year = $("#year").val();

            var url = app_url+'/work-planning';

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="hidden" name="month" value="'+month+'" />' +
                '<input type="hidden" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@endsection