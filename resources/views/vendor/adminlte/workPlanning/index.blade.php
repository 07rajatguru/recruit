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

@if(isset($page) && $page == 'Self')
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
@endif

@if(isset($page) && $page == 'Team')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('teamworkplanning.status',array('pending',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#8FB1D5;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Pending">Pending ({{ $pending }})</div></a>
            </div>

            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('teamworkplanning.status',array('approved',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#32CD32;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Approved">Approved ({{ $approved }})</div></a>
            </div>

            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('teamworkplanning.status',array('rejected',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#F08080;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Rejected">Rejected ({{ $rejected }})</div></a>
            </div>
        </div>
    </div><br/>
@endif

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

        @if(isset($work_planning_res) && $work_planning_res != '')
            @foreach ($work_planning_res as $key => $value)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a class="fa fa-circle" href="{{ route('workplanning.show',$value['id']) }}" title="Show"></a>

                        @if($user_id == $value['added_by_id'] || $user_id == $superadmin_user_id)
                            <a class="fa fa-edit" href="{{ route('workplanning.edit',$value['id']) }}" title="Edit"></a>
                        @endif
                        
                        @permission(('work-planning-delete'))
                            @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'workplanning','display_name'=>'Work Planning'])
                        @endpermission

                        @if($user_id == $value['added_by_id'])
                            @include('adminlte::partials.sendWorkPlanningReport', ['data' => $value, 'name' => 'workplanning'])
                        @endif

                        @if($user_id != $value['added_by_id'])
                            @include('adminlte::partials.addWorkPlanningRemarks', ['data' => $value, 'name' => 'workplanning','page' => $page])
                        @endif
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
        @endif
    </tbody>
</table>

<input type="hidden" name="page" id="page" value="{{ $page }}">
<input type="hidden" name="old_task_id" id="old_task_id" value="">
@stop

@section('customscripts')
    <script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {

            $(".task").select2();

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
            var page = $("#page").val();

            if(page == 'Self') {

                var url = app_url+'/work-planning';
            }
            if(page == 'Team') {
                
                var url = app_url+'/team-work-planning/';
            }

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="hidden" name="month" value="'+month+'" />' +
                '<input type="hidden" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }

        function setData(wp_id) {

            // Clear Previous Task Data
            var old_task_id = $("#old_task_id").val();
            $(".task_list_"+old_task_id).html("");
            
            var token = $('input[name="csrf_token"]').val();
            var app_url = "{!! env('APP_URL'); !!}";
            var task_id = $("#task_id_"+wp_id).val();
            
            $.ajax({

                type : 'GET',
                url : app_url+'/work-planning/getDetailsById',
                data : {task_id : task_id, '_token':token},
                dataType : 'json',

                success: function(data) {

                    var html = '';

                    html += '<div class="form-group"><strong>Projected Time : </strong><input type="text" name="projected_time" id="projected_time_'+task_id+'" value="'+data.projected_time+'" class="form-control" disabled></div>';

                    html += '<div class="form-group"><strong>Actual Time : </strong><input type="text" name="actual_time" id="actual_time_'+task_id+'" value="'+data.actual_time+'" class="form-control" disabled></div>';

                    html += '<div class="form-group"><strong>Remarks : </strong><textarea id="remarks_'+task_id+'" name="remarks" class="form-control" disabled>'+data.remarks+'</textarea></div>';

                    html += '<div class="form-group"><strong>Reporting Manager / HR Remarks : </strong><textarea id="rm_hr_remarks_'+task_id+'" name="rm_hr_remarks" class="form-control">'+data.rm_hr_remarks+'</textarea></div>';

                    $(".task_list_"+task_id).html("");
                    $(".task_list_"+task_id).show();
                    $(".task_list_"+task_id).append(html);

                    setRemarksEditor(task_id);
                    setHRRemarksEditor(task_id);

                    $("#old_task_id").val(task_id);
                }
            });
        }

        function setRemarksEditor(task_id) {

            CKEDITOR.replace('remarks_'+task_id+'', {

                filebrowserUploadUrl: '{{ route('emailbody.image',['_token' => csrf_token() ]) }}',
                customConfig: '/js/ckeditor_config.js',
                height: '100px',
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
        }

        function setHRRemarksEditor(task_id) {

            CKEDITOR.replace('rm_hr_remarks_'+task_id+'', {

                filebrowserUploadUrl: '{{ route('emailbody.image',['_token' => csrf_token() ]) }}',
                customConfig: '/js/ckeditor_config.js',
                height: '100px',
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
        }
    </script>
@endsection