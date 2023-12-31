@extends('adminlte::page')

@section('title', 'Late In / Early Go')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('late-early.add') }}">Add New Request
                </a>
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

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Late In / Early Go Requests ({{ $count }})</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    {{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control'))}}
                </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
                </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-1 col-sm-1 col-md-1">
                <a href="{{ route('late-early.status',array('pending',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="height:35px;background-color:#8FB1D5;font-weight: 600;border-radius: 50px;padding:9px 0px 0px 9px;text-align: center;width:120px;margin-left: -5px;" title="Pending">Pending ({{ $pending }})</div></a>
            </div>

            <div class="col-xs-1 col-sm-1 col-md-1">
                <a href="{{ route('late-early.status',array('approved',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="height:35px;background-color:#32CD32;font-weight: 600;border-radius: 50px;padding:9px 0px 0px 9px;text-align: center;width:120px;margin-left: 25px;" title="Approved">Approved ({{ $approved }})</div></a>
            </div>

            <div class="col-xs-1 col-sm-1 col-md-1">
                <a href="{{ route('late-early.status',array('rejected',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="height:35px;background-color:#F08080;font-weight: 600;border-radius: 50px;padding:9px 0px 0px 9px;text-align: center;width:120px;margin-left: 55px;" title="Rejected">Rejected ({{ $rejected }})</div></a>
            </div>
        </div>
    </div>
    <br/><br/>
    
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="leave_table">
        <thead>
            <tr>
                <th>No</th>
                <th width="40px">Action</th>
                <th>User Name</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Leave Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
            @foreach($leave_details as $key => $value)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a class="fa fa-circle" title="Show" href="{{ route('late-early.reply',\Crypt::encrypt($value['id'])) }}"></a>

                        @if($user_id == $value['user_id'])
                            <a class="fa fa-edit" title="edit" href="{{ route('late-early.edit',\Crypt::encrypt($value['id'])) }}"></a>
                        @endif

                        @permission(('leave-delete'))
                            @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'late-early','display_name'=>'Leave Application'])
                        @endpermission

                        {{-- @if($user_id == $value['user_id'])
                            @include('adminlte::partials.sendLeaveEmail', ['data' => $value, 'name' => 'late-early'])
                        @endif --}}
                    </td>

                    <td>{{ $value['user_name'] }}</td>
                    <td>{{ $value['subject'] }}</td>
                    <td>{{ $value['date'] }}</td>
                    <td>{{ $value['leave_type'] }}</td>

                    @if($value['status'] == 0)
                        <td style="background-color:#8FB1D5;">Pending</td>
                    @elseif($value['status'] == 1)
                        <td style="background-color:#32CD32;">Approved</td>
                    @elseif($value['status'] == 2)
                        <td style="background-color:#F08080;">Rejected</td>
                    @endif
                </tr>
            @endforeach
        </tbody>        
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var table = jQuery('#leave_table').DataTable({
                responsive: true,
                "pageLength": 100,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });
            new jQuery.fn.dataTable.FixedHeader( table );

            $("#month").select2();
            $("#year").select2();
        });

        function select_data() {

            var app_url = "{!! env('APP_URL'); !!}";
            var month = $("#month").val();
            var year = $("#year").val();

            var url = app_url+'/late-in-early-go';

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