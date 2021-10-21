@extends('adminlte::page')

@section('title', 'Leave')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('leave.add') }}">Add New Leave Application</a>
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

    @if($user_id == $super_admin_userid)
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Leave Applications</h2>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Leave Applications ({{ $count }})</h2>
                </div>
            </div>
        </div>
    @endif

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                {{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control'))}}
            </div>
        </div>

        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
            </div>
        </div>

        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
            </div>
        </div>

        @if($user_id == $super_admin_userid)
        @else

            <div class="box-body col-xs-2 col-sm-2 col-md-2">
                <a style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#00c0ef !important;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;cursor: pointer;width: 180px;" title="Total Leave">Total Leave ({{ $leave_balance->leave_total or 0 }})</div>
                </a>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2">
                <a style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#00a65a !important;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;cursor: pointer;width: 180px;" title="Leave Taken">Leave Taken ({{ $leave_balance->leave_taken or 0 }})</div>
                </a>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2">
                <a style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#dd4b39 !important;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;cursor: pointer;width: 180px;" title="Leave Remainings">Leave Remainings ({{ $leave_balance->leave_remaining or 0 }})</div></a>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('leave.status',array('pending',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#8FB1D5;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Pending">Pending ({{ $pending }})</div></a>
            </div>

            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('leave.status',array('approved',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#32CD32;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Approved">Approved ({{ $approved }})</div></a>
            </div>

            <div class="col-md-2" style="width: 15%;">
                <a href="{{ route('leave.status',array('not-approved',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#F08080;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Rejected">Not Approved ({{ $not_approved }})</div></a>
            </div>
        </div>
    </div><br/>

    <div class="table-responsive">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="leave_table">
            <thead>
                <tr>
                    <th>No</th>
                    <th width="40px">Action</th>
                    <th>User Name</th>
                    <th>Sujbect</th>
                    <th>From date</th>
                    <th>To Date</th>
                    <th>Leave Type</th>
                    <th>Leave Category</th>
                    <th>Status</th>
                </tr>
            </thead>
            <?php $i=0; ?>
            <tbody>
                @foreach($leave_details as $key => $value)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <a class="fa fa-circle" title="Show" href="{{ route('leave.reply',$value['id']) }}"></a>

                            <a class="fa fa-edit" title="edit" href="{{ route('leave.edit',$value['id']) }}"></a>

                            @permission(('leave-delete'))
                                @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'leave','display_name'=>'Leave Application'])
                            @endpermission

                            @if($user_id == $value['user_id'])
                                @include('adminlte::partials.sendLeaveEmail', ['data' => $value, 'name' => 'leave'])
                            @endif
                        </td>
                        <td>{{ $value['user_name'] }}</td>
                        <td>{{ $value['subject'] }}</td>
                        <td>{{ $value['from_date'] }}</td>
                        <td>{{ $value['to_date'] }}</td>
                        <td>{{ $value['leave_type'] }}</td>
                        <td>{{ $value['leave_category'] }}</td>

                        @if($value['status'] == 0)
                            <td style="background-color:#8FB1D5;">Pending</td>
                        @elseif($value['status'] == 1)
                            <td style="background-color:#32CD32;">Approved</td>
                        @elseif($value['status'] == 2)
                            <td style="background-color:#F08080;">Upapproved</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>        
        </table>
    </div>

    <input type="hidden" name="status" id="status" value="{{ $status }}">
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#leave_table').DataTable( {
                responsive: true,
                "pageLength": 100,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });
            new jQuery.fn.dataTable.FixedHeader( table );
        });

        function select_data() {

            var app_url = "{!! env('APP_URL'); !!}";
            var month = $("#month").val();
            var year = $("#year").val();
            var status = $("#status").val();

            if(status == 0) {
                status = 'pending';
            }
            else if(status == 1) {
                status = 'approved';
            }
            else if(status == 2) {
                status = 'not-approved';
            }

            var url = app_url+'/leave/'+status+'/'+month+'/'+year;

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="hidden" name="month" value="'+month+'" />' +
                '<input type="hidden" name="year" value="'+year+'" />' +
                '<input type="hidden" name="status" value="'+status+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@endsection