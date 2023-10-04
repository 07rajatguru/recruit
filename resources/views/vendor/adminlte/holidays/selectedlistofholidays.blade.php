@extends('adminlte::page')

@section('title', 'List of Selected Holidays')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h3>List of Selected Holidays</h3>
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
        <div class="box-body col-xs-2 col-sm-5 col-md-2">
            <div class="form-group">
                {{Form::select('s_user_id',$users,$uid, array('id'=>'s_user_id','class'=>'form-control users_append'))}}
            </div>
        </div>

        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-12"></div><br/>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <table border="1" cellpadding="0" cellspacing="0" width="500" style="font-family:Helvetica,Arial,sans-serif;" align="center">
                        <tr>
                            <td width="500" style="font-family:Cambria, serif;">
                                <table width="500" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                    <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 14.0pt;">
                                        <td align="center" style="border: 1px solid black;width: 80px;"><b>Sr. No.</b></td>
                                        <td align="center" style="border: 1px solid black;"><b>Fixed Holiday Leaves</b></td>
                                    </tr>

                                    <?php $i=0; ?>
                                    @foreach($fixed_holiday_list as $key => $value)
                                        <tr style="font-family:Cambria, serif;font-size: 12.0pt;">
                                            <td align="center">{{ ++$i }}</td>
                                            <td align="left" style="padding-left:10px;">
                                            {{ $value['title'] }} ( {{ $value['date'] }} - {{ $value['day'] }} )</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <table border="1" cellpadding="0" cellspacing="0" width="500" style="font-family:Helvetica,Arial,sans-serif;" align="center">
                        <tr>
                            <td width="500" style="font-family:Cambria, serif;">
                                <table width="500" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                    <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 14.0pt;">
                                        <td align="center" style="border: 1px solid black;width: 80px;"><b>Sr. No.</b></td>
                                        <td align="center" style="border: 1px solid black;"><b>Selected Optional Holiday Leaves</td>
                                        <td align="center" style="border: 1px solid black;"><b>Status</td>
                                    </tr>

                                    <?php $i=0; ?>
                                    @foreach($optional_holiday_list as $key => $value)

                                        @if($value['title'] != 'Any other Religious Holiday for respective community - Please specify')
                                            <tr style="font-family:Cambria, serif;font-size: 12.0pt;">
                                                <td align="center">{{ ++$i }}</td>
                                                <td align="left" style="padding-left: 10px;">{{ $value['title'] }} ( {{ $value['date'] }} - {{ $value['day'] }} )</td>
                                                <td align="center">
                                                    @if($value['status'] == 'Pending')
                                                        @if($logged_in_user_id != $uid)
                                                            <a data-toggle="modal" href="#modal-change-status" title="Change Status" class="btn btn-info change-status" data-u_holiday_id="{{ $value['id'] }}" data-u_user_id="{{ $uid }}">Update Status</a>
                                                        @endif
                                                    @elseif($value['status'] == 'Approved')
                                                        <label class="label label-success">{{ $value['status'] }} by {{ $value['status_update_by'] }}</label>
                                                    @elseif($value['status'] == 'Rejected')
                                                        <label class="label label-danger">{{ $value['status'] }} by {{ $value['status_update_by'] }}</label>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($logged_in_user_id == $uid)
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <a class="btn btn-primary" href="{{ route('listof.holidays',$uid) }}">Modify Optional Holiday List</a>
            </div>
        </div>
    @endif


    <div id="modal-change-status" class="modal text-left fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h2 class="modal-title">Update status</h2>
                </div>
                
                {!! Form::open(['method' => 'POST', 'route' => 'holidays.updatedholidays']) !!}
                    <div class="modal-body">
                        <div class="status-round">
                            Are you sure you want to change the status of optional holiday?
                        </div>
                        <input type="hidden" name="u_holiday_id" id="u_holiday_id" value="">
                        <input type="hidden" name="u_user_id" id="u_user_id" value="">
                    </div>
 
                    <div class="modal-footer" id="d_footer1">
                        <input type="submit" name="submit" id="a_submit" value="Approved" class="btn btn-primary">
                        <input type="submit" name="submit" id="r_submit" value="Rejected" class="btn btn-danger">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('customscripts')
<script type="text/javascript">
    $(document).ready(function() {
        $("#s_user_id").select2();

        $('.change-status').on('click', function() {
            var u_holiday_id = $(this).data('u_holiday_id');
            var u_user_id = $(this).data('u_user_id');

            $("#u_holiday_id").val(u_holiday_id);
            $("#u_user_id").val(u_user_id);
        });
    });

    function select_data() {
        var s_user_id = $("#s_user_id").val();
        var app_url = "{!! env('APP_URL'); !!}";

        var url = app_url+'/list-of-selected-holidays/'+s_user_id;
        if (s_user_id > 0) {
            window.location.href = url;
        } else {
            alert("Please Select User");
        }
    }
</script>
@endsection