@section('customs_css')
<style>
    .error
    {
        color:#f56954 !important;
    }
    tbody > tr > td:first-child {
      text-align: center;
    }
</style>
@endsection

@extends('adminlte::page')

@section('title', 'Work From Home Request')

@section('content_header')
@stop

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h3>{!! $work_from_home_res['subject'] !!}</h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('workfromhome.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <br/>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>User Name</th>
                            <td>{{ $work_from_home_res['added_by'] }}</td>
                            <th>From Date</th>
                            <td>{{ $work_from_home_res['from_date'] }}</td>
                            <th>To Date</th>
                            <td>{{ $work_from_home_res['to_date'] }}</td>
                            <th>Status</th>

                            @if($work_from_home_res['status'] == 0)
                                <td style="background-color:#8FB1D5;">Pending</td>
                            @elseif($work_from_home_res['status'] == 1)
                                <td style="background-color:#32CD32;">Approved</td>
                            @elseif($work_from_home_res['status'] == 2)
                                <td style="background-color:#F08080;">Rejected</td>
                            @endif
                        </tr>

                        <tr>
                            <th>Reason</th>
                            <td colspan="7">{!! $work_from_home_res['reason'] !!}</td>
                        </tr>

                        @if(isset($work_from_home_res['remarks']) && $work_from_home_res['remarks'] != '')
                            <tr>
                                <th>Reason of Rejection</th>
                                <td colspan="7">{!! $work_from_home_res['remarks'] !!}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($loggedin_user_id == $work_from_home_res['user_id'])

        @if($work_from_home_res['status'] == 1)
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="button" class="btn btn-success" disabled="disabled">Approved by {{ $work_from_home_res['appr_rejct_by'] }}</button>
            </div>
        @elseif($work_from_home_res['status'] == 2)
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="button" class="btn btn-danger" disabled="disabled">Rejected by {{ $work_from_home_res['appr_rejct_by'] }}</button>
            </div>
        @endif
        
    @else
        @if($work_from_home_res['status'] == 0)
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-success" onclick="updateStatus('Approved')" title="Approve">Approve</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-danger" onclick="updateStatus('Rejected')" title="Reject">Reject</button>
            </div>
        @elseif($work_from_home_res['status'] == 1)
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="button" class="btn btn-success" disabled="disabled">Approved by {{ $work_from_home_res['appr_rejct_by'] }}</button>&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-danger" onclick="updateStatus('Rejected')" title="Reject">Reject</button>
            </div>
        @elseif($work_from_home_res['status'] == 2)
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-success" onclick="updateStatus('Approved')" title="Approve">Approve</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-danger" disabled="disabled">Rejected by {{ $work_from_home_res['appr_rejct_by'] }}</button>
            </div>
        @endif
    @endif

    <input type="hidden" name="work_from_home_id" id="work_from_home_id" value="{{ $work_from_home_id }}">
    <input type="hidden" name="loggedin_user_id" id="loggedin_user_id" value="{{ $loggedin_user_id }}">
    <input type="hidden" name="user_name" id="user_name" value="{{ $work_from_home_res['added_by'] }}">
    <input type="hidden" name="user_id" id="user_id" value="{{ $work_from_home_res['user_id'] }}">
    <input type="hidden" name="appr_rejct_by" id="appr_rejct_by" value="{{ $work_from_home_res['appr_rejct_by'] }}">
    <input type="hidden" name="from_date" id="from_date" value="{{ $work_from_home_res['from_date'] }}">
    <input type="hidden" name="to_date" id="to_date" value="{{ $work_from_home_res['to_date'] }}">
    <input type="hidden" name="check" id="check" value="">

    <!-- Remarsk Modal Popup -->
    <div id="remarksmodal" class="modal text-left fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add Remarks</h4>
                </div>

                <div class="modal-body">
                    <div class="ac_mngr_cls">
                        <div class="form-group">
                            <strong>Remarks: <span class = "required_fields">*</span></strong>
                            {!! Form::textarea('remarks', null, array('id'=>'remarks','placeholder' => 'Remarks','class' => 'form-control','rows' => '5')) !!}
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="hideModal();">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('customscripts')
<script type="text/javascript">

    function updateStatus(check) {
        
        var work_from_home_id = $("#work_from_home_id").val();
        var app_url = "{!! env('APP_URL') !!}";
        var token = $("input[name=_token]").val();
        var user_name = $("#user_name").val();
        var loggedin_user_id = $("#loggedin_user_id").val();
        var user_id = $("#user_id").val();
        var appr_rejct_by = $("#appr_rejct_by").val();

        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();

        $("#check").val(check);

        if(check == 'Rejected') {

            $("#remarksmodal").modal('show');
        }
        else {

            var remarks = '';

            $.ajax({
                type: 'POST',
                url:app_url+'/work-from-home/'+work_from_home_id+'/show',
                data: {work_from_home_id: work_from_home_id, 'check':check, '_token':token, user_name:user_name, loggedin_user_id:loggedin_user_id, user_id:user_id, appr_rejct_by:appr_rejct_by, from_date:from_date, to_date:to_date,remarks:remarks},
                dataType:'json',

                success: function(data) {

                    if (data == 'success') {

                        window.location.reload();
                        alert('Reply Sent Successfully.');
                    }
                }
            });
        }
    }

    function hideModal() {

        var remarks = $("#remarks").val();

        if(remarks == '') {

            alert("Please Add Remarks");
            return false;
        }

        $("#remarksmodal").modal('hide');
        
        var work_from_home_id = $("#work_from_home_id").val();
        var app_url = "{!! env('APP_URL') !!}";
        var token = $("input[name=_token]").val();
        var user_name = $("#user_name").val();
        var loggedin_user_id = $("#loggedin_user_id").val();
        var user_id = $("#user_id").val();
        var appr_rejct_by = $("#appr_rejct_by").val();

        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var check = $("#check").val();

        $.ajax({

            type: 'POST',
            url:app_url+'/work-from-home/'+work_from_home_id+'/show',
            data: {work_from_home_id: work_from_home_id, 'check':check, '_token':token, user_name:user_name, loggedin_user_id:loggedin_user_id, user_id:user_id, appr_rejct_by:appr_rejct_by, from_date:from_date, to_date:to_date,remarks:remarks},
            dataType:'json',
            success: function(data) {
                if (data == 'success') { 
                    window.location.reload();
                    alert('Reply Sent Successfully.');
                }
            }
        });
    }
</script>
@endsection