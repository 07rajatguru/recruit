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

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if($action == 'edit')
                <h2>Edit Work Planning Sheet</h2>
            @else
                <h2>Add Work Planning</h2>

                @permission('tomorrow-work-planning-add')

                    <?php

                        $utc_current_date = date('Y-m-d') . " " . date('H:i:s');
                        $dt_current_date = new \DateTime($utc_current_date);
                        $tz_current_date = new \DateTimeZone('Asia/Kolkata');

                        $dt_current_date->setTimezone($tz_current_date);
                        $time = strtotime($dt_current_date->format('H:i:s'));
                        $current_time = date("H:i", $time);
                    ?>

                    <div role="tabpanel">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="yesterday">
                                <a href="" role="tab" data-toggle="tab" style="font-size:15px;color: black;" title="Yesterday" onclick="setDateValue('Yesterday');"><b>Yesterday</b></a>
                            </li>

                            <li role="presentation" class="today active">
                                <a href="" role="tab" data-toggle="tab" style="font-size:15px;color: black;" title="Today" onclick="setDateValue('Today');"><b>Today</b></a>
                            </li>

                            @if($current_time >= '18:00')
                                <li role="presentation" class="tomorrow">
                                    <a href="" role="tab" data-toggle="tab" style="font-size:15px;color: black;" title="Tomorrow" onclick="setDateValue('Tomorrow');"><b>Tomorrow</b></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endpermission
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('workplanning.index') }}">Back</a>
        </div>
    </div>
</div>

@if($action == 'edit')
    {!! Form::model($work_planning_res,['method' => 'PATCH', 'files' => true, 'route' => ['workplanning.update', $work_planning_res['id']],'id'=>'work_planning_form', 'autocomplete' => 'off','onsubmit' => "return sendEmail()"]) !!}
@else
    {!! Form::open(['files' => true, 'route' => 'workplanning.store','id'=>'work_planning_form', 'autocomplete' => 'off']) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6"></div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Select Work Location : </strong>
                            {!! Form::select('work_type',$work_type,$selected_work_type, array('id'=>'work_type','class' => 'form-control','tabindex' => '1')) !!}
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Logged-in Time : </strong>
                            {!! Form::text('loggedin_time',$loggedin_time, array('id' => 'loggedin_time','class' => 'form-control','tabindex' => '2','readonly' => 'true')) !!}
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Logged-out Time : </strong>
                            {!! Form::text('loggedout_time',$loggedout_time, array('id' => 'loggedout_time','class' => 'form-control','tabindex' => '3','readonly' => 'true')) !!}
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Work Planning Time : </strong>
                            {!! Form::text('work_planning_time',$work_planning_time, array('id' => 'work_planning_time','class' => 'form-control','tabindex' => '4','readonly' => 'true')) !!}
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Status Time : </strong>
                            {!! Form::text('work_planning_status_time', $work_planning_status_time, array('id' => 'work_planning_status_time','class' => 'form-control','tabindex' => '5','readonly' => 'true')) !!}
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Minimum Working Hours : </strong>
                            {!! Form::text('remaining_time',$minimum_working_hours, array('id' => 'remaining_time','class' => 'form-control','tabindex' => '6','readonly' => 'true','style' => 'background-color:#B0E0E6;')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6"></div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                @if($action == 'add')
                    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="work_planning_table" style="border:1px solid black;">
                        <thead>
                            <tr style="border:1px solid black;">
                               <th width="5%" style="border:1px solid black;">Sr No.</th>
                               <th width="20%" style="border:1px solid black;">Task</th>
                               <th width="10%" style="border:1px solid black;">Projected Time / <br/>Actual Time</th>
                               <!-- <th style="border:1px solid black;">Actual Time </th> -->
                               <th width="30%" style="border:1px solid black;">Remarks</th>
                               <th width="30%" style="border:1px solid black;">Reporting Manager / HR Remarks</th>
                            </tr>
                        </thead>

                        <tbody>    
                            <?php $tabindex = 6; ?>
                            @for($i=1; $i<=5; $i++)
                                <tr class="row_{{ $i }}" style="border:1px solid black;">
                                    <td style="border:1px solid black;text-align: center;">
                                        {{ $i }}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!!Form::textarea('task[]',null, array('placeholder' => 'Task','id' => 'task_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'rows' => 3)) !!}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!! Form::select('projected_time[]',$time_array,$selected_projected_time, array('id' => 'projected_time_'.$i, 'class' => 'form-control','tabindex' => $tabindex++,'onchange'=>'setTotalProjectedTime()')) !!} <br/><br/>

                                        {!! Form::select('actual_time[]',$time_array,$selected_actual_time, array('id' => 'actual_time_'.$i,'class' => 'form-control', 'tabindex' => $tabindex++,'disabled' => 'true','style' => 'width:130px;','onchange'=>'setTotalActualTime()')) !!}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!!Form::textarea('remarks[]',null, array('placeholder' =>'Remarks','id' => 'remarks_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'rows' => 5)) !!}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!!Form::textarea('rm_hr_remarks[]',null, array('placeholder' =>'RM / HR Remarks','id' => 'rm_hr_remarks_'.$i,'class' => 'form-control', 'tabindex' => $tabindex++,'rows' => 5,'disabled' => true)) !!}
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                @endif
                @if($action == 'edit')
                    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="work_planning_table" style="border:1px solid black;">
                        <thead>
                            <tr style="border:1px solid black;">
                               <th width="5%" style="border:1px solid black;">Sr No.</th>
                               <th width="20%" style="border:1px solid black;">Task</th>
                               <th width="10%" style="border:1px solid black;">Projected Time / <br/>Actual Time</th>
                               <th width="30%" style="border:1px solid black;">Remarks</th>
                               <th width="30%" style="border:1px solid black;">Reporting Manager / HR Remarks</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                @endif

                <div class="col-md-12" style="margin-left:-28px;">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Total Projected Time : </strong>
                            {!! Form::text('total_projected_time',null, array('id' => 'total_projected_time','class' => 'form-control','readonly' => 'true')) !!}
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Total Actual Time : </strong>
                            {!! Form::text('total_actual_time',null, array('id' => 'total_actual_time','class' => 'form-control','readonly' => 'true')) !!}
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            <strong>Link : </strong>
                            {!! Form::text('link',null, array('id' => 'link','class' => 'form-control','style' => 'width:815px;')) !!}
                        </div>
                    </div>
                </div>                
            </div>
            
            <div style="margin-left:500px;">
                <button type="button" disabled="true" class="btn btn-primary" id="remove_row" onclick="RemoveRow();">Remove</button>
                <button type="button" class="btn btn-primary" onclick="AddRow();" id="add_row">Add</button>
            </div><br/>
        </div>
    </div>
     
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        {!! Form::submit(isset($work_planning_res) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
    </div>

    <input type="hidden" id="action" name="action" value="{{ $action }}">

    @if( $action == 'add')
        <input type="hidden" id="row_cnt" name="row_cnt" value="6">
        <input type="hidden" id="plus_one_hour_time" name="plus_one_hour_time" value="{{ $plus_one_hour_time }}">

        <input type="hidden" id="actual_loggedin_time" name="actual_loggedin_time" value="{{ $actual_loggedin_time }}">
    @endif

    @if($action == 'edit')
        <input type="hidden" value="{!! $id !!}" name="work_planning_id" id="work_planning_id">
        <input type="hidden" id="row_cnt" name="row_cnt" value="1">
        <input type="hidden" value="{!! $loggedin_userid !!}" name="loggedin_userid" id="loggedin_userid">
    @endif
</div>

<!-- Modal Start -->
<div class="modal text-left fade" id="alertModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Work Planning</h4>
            </div>
            <div class="modal-body">
                <p>Have you Reported Late to Work?</p>

                <div class="yes_detail_class" style="display:none;">
                    {!! Form::radio('report_delay','Late in / Early Go',false,['id' => 'report_delay','onchange' => 'displayTextArea("Late in / Early Go")']) !!}
                    {!! Form::label('Late in / Early Go') !!} &nbsp;

                    {!! Form::radio('report_delay','Half Day',false,['id' => 'report_delay','onchange' => 'displayTextArea("Half Day")']) !!}
                    {!! Form::label('Half Day') !!} &nbsp;

                    {!! Form::radio('report_delay','There is delay of Sending Report',false,['id' => 'report_delay','onchange' => 'displayTextArea("There is delay of Sending Report")']) !!}
                    {!! Form::label('There is delay of Sending Report') !!}&nbsp;

                    {!! Form::radio('report_delay','Others',false,['id' => 'report_delay','onchange' => 'displayTextArea("Others")']) !!}
                    {!! Form::label('Others') !!}

                    <div class="form-group report_delay_conent_cls" style="display:none;">
                        <br/><strong>&nbsp;If others, please specify :</strong>
                        {!! Form::textarea('report_delay_content', null, array('id' => 'report_delay_content','placeholder' => 'Specify Reason','class' => 'form-control','rows' => '5')) !!}
                    </div>
                </div>
                <div class="no_detail_class" style="display:none;">
                    <div class="form-group">
                        <br/><strong>&nbsp; Please Specify Reason :</strong>
                        {!! Form::textarea('no_report_content', null, array('id' => 'no_report_content','placeholder' => 'Specify Reason','class' => 'form-control','rows' => '5')) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer first_footer">
                <button type="button" class="btn btn-primary" onclick="displaybuttons('Yes');">Yes
                </button>
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">No</button> -->
                <button type="button" class="btn btn-default" onclick="displaybuttons('No');">No</button>
            </div>
            <div class="modal-footer second_footer" style="display:none;">
                <button type="button" class="btn btn-primary" onclick="submitform();">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->

<input type="hidden" name="email_value" id="email_value" value="">
<input type="hidden" name="date_value" id="date_value" value="">

{!! Form::close() !!}

@section('customscripts')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
<script type="text/javascript">

    $(document).ready(function() {

        setDateValue('Today');

        $("#work_type").select2();

        var action = $("#action").val();

        if(action == 'add') {

            for(j = 1; j <= 5; j++) {

                $("#projected_time_"+j).select2({width:"130px"});

                CKEDITOR.replace( 'remarks_'+j+'', {
                    customConfig: '/js/ckeditor_config.js',
                    height: '100px',
                });

                CKEDITOR.replace( 'rm_hr_remarks_'+j+'', {
                    customConfig: '/js/ckeditor_config.js',
                    height: '100px',
                });
            }

            checkTime();
            jQuery(document).on('focus', '.select2', function() {
                jQuery(this).siblings('select').select2('open');
            });

            $("#work_planning_form").validate({
                rules: {
                    "task[]": {
                        required: true
                    }
                },
                messages: {
                    "task[]": {
                        required: "Task is Required Field."
                    }
                }
            });
        }

        var work_planning_id = $("#work_planning_id").val();

        if(action == "edit") {
            loadDetails(work_planning_id);
        }
    });

    function setTotalProjectedTime() {

        // Set Total Projected Time
        var row_cnt = $("#row_cnt").val();
        var projected_time_array = [];

        for(j = 1; j < row_cnt; j++) {
                
            var projected_time = $("#projected_time_"+j).val();
            projected_time_array.push(projected_time);
        }

        const sum = projected_time_array.reduce((acc, time) => acc.add(moment.duration(time)), moment.duration());

        var final_working_hours = [Math.floor(sum.asHours()), sum.minutes(), sum.seconds()].join(':');

        var total_projected_time = final_working_hours.split(':');

        if(total_projected_time[0] < 10) {
            var hh = "0" + total_projected_time[0];
        }
        else {
            var hh = total_projected_time[0];
        }

        if(total_projected_time[1] < 10) {
            var mm = "0" + total_projected_time[1];
        }
        else {
            var mm = total_projected_time[1];
        }

        var disply_total_projected_time = hh + ":" + mm + ":00";
        $("#total_projected_time").val(disply_total_projected_time);
    }

    function setTotalActualTime() {

        // Set Total Actual Time
        var row_cnt = $("#row_cnt").val();
        var actual_time_array = [];

        for(j = 1; j < row_cnt; j++) {
                
            var actual_time = $("#actual_time_"+j).val();
            actual_time_array.push(actual_time);
        }

        const sum = actual_time_array.reduce((acc, time) => acc.add(moment.duration(time)), moment.duration());

        var final_working_hours = [Math.floor(sum.asHours()), sum.minutes(), sum.seconds()].join(':');

        var total_actual_time = final_working_hours.split(':');

        if(total_actual_time[0] < 10) {
            var hh = "0" + total_actual_time[0];
        }
        else {
            var hh = total_actual_time[0];
        }

        if(total_actual_time[1] < 10) {
            var mm = "0" + total_actual_time[1];
        }
        else {
            var mm = total_actual_time[1];
        }

        var disply_total_actual_time = hh + ":" + mm + ":00";
        $("#total_actual_time").val(disply_total_actual_time);
    }

    function AddRow() {

        var row_cnt = $("#row_cnt").val();
        var action = {!! json_encode($action) !!};
        var time_array_1 = <?php echo json_encode($time_array); ?>;

        var table = document.getElementById("work_planning_table");
        var row = table.insertRow(-1);
        row.className = 'row_'+row_cnt+'';
        row.style.border = '1px solid black';

        var cell1 = row.insertCell(0);
        cell1.style.border = '1px solid black';
        cell1.innerHTML = row_cnt;

        var cell2 = row.insertCell(1);
        cell2.style.border = '1px solid black';
        cell2.innerHTML = '<td style="border:1px solid black;"><textarea name="task[]" placeholder="Task" id="task_'+row_cnt+'" class="form-control" rows="3"></textarea></td>';

        var cell3 = row.insertCell(2);
        cell3.style.border = '1px solid black';

        if(action == "add") {

            cell3.innerHTML =  '<td style="border:1px solid black;"><select class="form-control" name="projected_time[]" id="projected_time_'+row_cnt+'" onchange="setTotalProjectedTime()"></select> <br/><br/><select class="form-control" name="actual_time[]" id="actual_time_'+row_cnt+'" readonly=true></select></td>';
        }
        else {

            cell3.innerHTML = '<td style="border:1px solid black;"><select class="form-control" name="projected_time[]" id="projected_time_'+row_cnt+'" onchange="setTotalProjectedTime()"></select> <br/><br/><select class="form-control" name="actual_time[]" id="actual_time_'+row_cnt+'" onchange="setTotalActualTime()"></select></td>';
        }

        var cell4 = row.insertCell(3);
        cell4.style.border = '1px solid black';
        cell4.innerHTML = '<td style="border:1px solid black;"><textarea name="remarks[]" placeholder="Remarks" id="remarks_'+row_cnt+'" class="form-control" rows="5"></textarea></td>';

        var cell5 = row.insertCell(4);
        cell5.style.border = '1px solid black';
        cell5.innerHTML = '<td style="border:1px solid black;"><textarea name="rm_hr_remarks[]" placeholder="RM / HR Remarks" id="rm_hr_remarks_'+row_cnt+'" class="form-control" rows="5" disabled></textarea></td>';

        $.each(time_array_1, function(key, value) {
            $('<option value="'+key+'">'+value+'</option>').appendTo($("#projected_time_"+row_cnt));
            $('<option value="'+key+'">'+value+'</option>').appendTo($("#actual_time_"+row_cnt));
        });
    
        $("#projected_time_"+row_cnt).select2();

        if(action == "add") {

            document.getElementById("actual_time_"+row_cnt).disabled = true;
        }
        else {
            $("#actual_time_"+row_cnt).select2();
        }

        CKEDITOR.replace( 'remarks_'+row_cnt+'', {
            customConfig: '/js/ckeditor_config.js',
            height: '100px',
        });

        CKEDITOR.replace( 'rm_hr_remarks_'+row_cnt+'', {
            customConfig: '/js/ckeditor_config.js',
            height: '100px',
        });

        var row_cnt_new = parseInt(row_cnt)+1;
        $("#row_cnt").val(row_cnt_new);

        document.getElementById("remove_row").disabled = false;
    }

    function RemoveRow() {

        var row_cnt = $("#row_cnt").val();

        if(row_cnt == 6) {

            document.getElementById("remove_row").disabled = true;
            return false;
        }

        var row_cnt_new = parseInt(row_cnt)-1;
        $(".row_" + row_cnt_new).remove();
        $("#row_cnt").val(row_cnt_new);

        setTotalProjectedTime();
        setTotalActualTime();
    }

    function loadDetails(work_planning_id) {
        var app_url = "{!! env('APP_URL'); !!}";
        $.ajax({

            type: 'GET',
            url: app_url+'/work-planning/getlist',
            data:{work_planning_id:work_planning_id},
            dataType: 'json',
            async:true,
            success: function (data) {

                if (data.length > 0) {

                    for(j = 0;j < data.length;j++) {

                        var work_planning_list_id = data[j].work_planning_list_id;
                        var task = data[j].task;
                        var projected_time = data[j].projected_time;
                        var actual_time = data[j].actual_time;
                        var remarks = data[j].remarks;
                        var rm_hr_remarks = data[j].rm_hr_remarks;
                        var added_by = data[j].added_by;

                        var loggedin_userid = $("#loggedin_userid").val();


                        var time_array_1 = <?php echo json_encode($time_array); ?>;
                        var row_cnt = $("#row_cnt").val();

                        var table = document.getElementById("work_planning_table");
                        var row = table.insertRow(-1);
                        row.className = 'row_'+row_cnt+'';
                        row.style.border = '1px solid black';

                        var cell1 = row.insertCell(0);
                        cell1.style.border = '1px solid black';
                        cell1.innerHTML = row_cnt;

                        if(added_by != loggedin_userid) {

                            var cell2 = row.insertCell(1);
                            cell2.style.border = '1px solid black';
                            cell2.innerHTML = '<td style="border:1px solid black;"><textarea name="task[]" placeholder="Task" id="task_'+row_cnt+'" class="form-control" rows="3" readonly>'+task+'</textarea></td>';

                            var cell3 = row.insertCell(2);
                            cell3.style.border = '1px solid black';
                            cell3.innerHTML = '<td style="border:1px solid black;"><select class="form-control" name="projected_time[]" id="projected_time_'+row_cnt+'" onchange="setTotalProjectedTime()" disabled></select> <br/><br/><select class="form-control" name="actual_time[]" id="actual_time_'+row_cnt+'" onchange="setTotalActualTime()" disabled></select></td>';

                            var cell4 = row.insertCell(3);
                            cell4.style.border = '1px solid black';
                            cell4.innerHTML = '<td style="border:1px solid black;"><textarea name="remarks[]" placeholder="Remarks" id="remarks_'+row_cnt+'" class="form-control" rows="5" readonly>'+remarks+'</textarea></td>';

                            if(rm_hr_remarks == null) {
                                rm_hr_remarks = '';
                            }

                            var cell5 = row.insertCell(4);
                            cell5.style.border = '1px solid black';
                            cell5.innerHTML = '<td style="border:1px solid black;"><textarea name="rm_hr_remarks[]" placeholder="RM / HR Remarks" id="rm_hr_remarks_'+row_cnt+'" class="form-control" rows="5">'+rm_hr_remarks+'</textarea></td>';   
                        }
                        else {

                            var cell2 = row.insertCell(1);
                            cell2.style.border = '1px solid black';
                            cell2.innerHTML = '<td style="border:1px solid black;"><textarea name="task[]" placeholder="Task" id="task_'+row_cnt+'" class="form-control" rows="3">'+task+'</textarea></td>';

                            var cell3 = row.insertCell(2);
                            cell3.style.border = '1px solid black';
                            cell3.innerHTML = '<td style="border:1px solid black;"><select class="form-control" name="projected_time[]" id="projected_time_'+row_cnt+'" onchange="setTotalProjectedTime()"></select> <br/><br/><select class="form-control" name="actual_time[]" id="actual_time_'+row_cnt+'" onchange="setTotalActualTime()"></select></td>';

                            var cell4 = row.insertCell(3);
                            cell4.style.border = '1px solid black';
                            cell4.innerHTML = '<td style="border:1px solid black;"><textarea name="remarks[]" placeholder="Remarks" id="remarks_'+row_cnt+'" class="form-control" rows="5">'+remarks+'</textarea></td>';

                            if(rm_hr_remarks == null) {
                                rm_hr_remarks = '';
                            }

                            var cell5 = row.insertCell(4);
                            cell5.style.border = '1px solid black';
                            cell5.innerHTML = '<td style="border:1px solid black;"><textarea name="rm_hr_remarks[]" placeholder="RM / HR Remarks" id="rm_hr_remarks_'+row_cnt+'" class="form-control" rows="5" readonly>'+rm_hr_remarks+'</textarea></td>';
                        }
                        

                        $.each(time_array_1, function(key1, value1) {

                            if(projected_time == key1) {

                                $('<option value="'+key1+'" selected>'+value1+'</option>').appendTo($("#projected_time_"+row_cnt));
                            }
                            else {
                                
                                $('<option value="'+key1+'">'+value1+'</option>').appendTo($("#projected_time_"+row_cnt));
                            }
                        });

                        $.each(time_array_1, function(key2, value2) {

                            if(actual_time == key2) {

                                $('<option value="'+key2+'" selected>'+value2+'</option>').appendTo($("#actual_time_"+row_cnt));
                            }
                            else {
                            
                                $('<option value="'+key2+'">'+value2+'</option>').appendTo($("#actual_time_"+row_cnt));
                            }
                        });

                        $("#projected_time_"+row_cnt).select2({width:"130px"});
                        $("#actual_time_"+row_cnt).select2({width:"130px"});
                        
                        CKEDITOR.replace( 'remarks_'+row_cnt+'', {
                            customConfig: '/js/ckeditor_config.js',
                            height: '100px',
                        });

                        CKEDITOR.replace( 'rm_hr_remarks_'+row_cnt+'', {
                            customConfig: '/js/ckeditor_config.js',
                            height: '100px',
                        });
                        
                        var row_cnt_new = parseInt(row_cnt)+1;
                        $("#row_cnt").val(row_cnt_new);

                        if(row_cnt_new < 5) {
                            document.getElementById("remove_row").disabled = true;
                        }
                        else {

                            document.getElementById("remove_row").disabled = false;
                        }
                    }
                }
            }
        });
    }

    function checkTime() {

        var time1 = $("#plus_one_hour_time").val();       
        var value1 = time1.split(':');

        var time2 = new Date();
        var current_time = time2.getHours() + ":" + time2.getMinutes() + ":" + time2.getSeconds();
        var value2 = current_time.split(':');

        var d1 = new Date(parseInt("2001",10),(parseInt("01",10))-1,parseInt("01",10),parseInt(value1[0],10),parseInt(value1[1],10),parseInt(value1[2],10));

        var d2 = new Date(parseInt("2001",10),(parseInt("01",10))-1,parseInt("01",10),parseInt(value2[0],10),parseInt(value2[1],10),parseInt(value2[2],10));

        var dd1 = d1.valueOf();
        var dd2 = d2.valueOf();

        var actual_loggedin_time = $("#actual_loggedin_time").val();

        if(dd2 > dd1 || actual_loggedin_time > '10:30') {

            $("#alertModal").modal('show');
            return false;
        }
        
        return true;
    }

    function displaybuttons(value) {

        if(value == 'Yes') {
            $(".yes_detail_class").show();
        }
        else if(value == 'No') {
            $(".no_detail_class").show();
        }

        $(".first_footer").hide();
        $(".second_footer").show();
    }

    function displayTextArea(value) {

        if(value == 'Others') {

            $(".report_delay_conent_cls").show();
        }
        else {
            $(".report_delay_conent_cls").hide();
        }
    }

    function submitform() {

        $('#alertModal').modal('hide');
    }

    function sendEmail() {

        var msg = "Send an email with updated details?";
        var confirmvalue = confirm(msg);

        if(confirmvalue) {

            $("#email_value").val(confirmvalue);
        }
        return true;
    }

    function setDateValue(day) {
        
        if(day == 'Today') {
            
            var utc_date = new Date().toJSON().slice(0,10).replace(/-/g,'-');
            $("#date_value").val(utc_date);
        }
        else if(day == 'Yesterday') {
            
            var a = new Date(new Date().setDate(new Date().getDate() - 1));
            var utc_date = a.toJSON().slice(0,10).replace(/-/g,'-');
            $("#date_value").val(utc_date);
        }
        else if(day == 'Tomorrow') {
            
            var a = new Date(new Date().setDate(new Date().getDate() + 1));
            var utc_date = a.toJSON().slice(0,10).replace(/-/g,'-');
            $("#date_value").val(utc_date);
        }
    }
</script>
@endsection