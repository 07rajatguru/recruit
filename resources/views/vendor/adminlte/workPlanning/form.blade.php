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
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('workplanning.index') }}">Back</a>
        </div>
    </div>
</div>

@if($action == 'edit')
    {!! Form::model($work_planning_res,['method' => 'PATCH', 'files' => true, 'route' => ['workplanning.update', $work_planning_res['id']],'id'=>'work_planning_form', 'autocomplete' => 'off']) !!}
@else
    {!! Form::open(['files' => true, 'route' => 'workplanning.store','id'=>'work_planning_form', 'autocomplete' => 'off','onsubmit' => "return hoursValidation()"]) !!}
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
                            {!! Form::text('work_planning_status_time',$work_planning_status_time, array('id' => 'work_planning_status_time','class' => 'form-control','tabindex' => '5','readonly' => 'true')) !!}
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Remaining Hours : </strong>
                            {!! Form::text('remaining_time',$remaining_time, array('id' => 'remaining_time','class' => 'form-control','tabindex' => '6','readonly' => 'true','style' => 'color:red;')) !!}
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
                                    <td style="border:1px solid black;text-align: center;">{{ $i }}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!!Form::textarea('task[]',null, array('placeholder' => 'Task','id' => 'task_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'rows' => 3)) !!}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!! Form::select('projected_time[]',$time_array,$selected_projected_time, array('id' => 'projected_time_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'onchange'=>'setRemainTime('.$i.')')) !!} <br/><br/>

                                        {!! Form::select('actual_time[]',$time_array,$selected_actual_time, array('placeholder' => 'Select Time','id' => 'actual_time_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'disabled' => 'true','style' => 'width:130px;')) !!}
                                    </td>

                                    <!-- <td style="border:1px solid black;">
                                        
                                    </td> -->

                                    <td style="border:1px solid black;">
                                        {!!Form::textarea('remarks[]',null, array('placeholder' =>'Remarks','id' => 'remarks_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'rows' => 5)) !!}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!!Form::textarea('rm_hr_remarks[]',null, array('placeholder' =>'RM / HR Remarks','id' => 'rm_hr_remarks_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'rows' => 5,'disabled' => true)) !!}
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

                <div class="form-group">
                    <strong>Link : </strong>
                    {!! Form::text('link',null, array('id' => 'link','class' => 'form-control')) !!}
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
    <input type="hidden" id="user_total_hours" name="user_total_hours" value="{{ $user_total_hours }}">
    <input type="hidden" id="user_half_day_hours" name="user_half_day_hours" value="{{ $user_half_day_hours }}">
    <input type="hidden" id="early_late_in_time" name="early_late_in_time" value="{{ $early_late_in_time }}">

    @if( $action == 'add')
        <input type="hidden" id="row_cnt" name="row_cnt" value="6">
        <input type="hidden" id="plus_one_hour_time" name="plus_one_hour_time" value="{{ $plus_one_hour_time }}">
    @endif

    @if($action == 'edit')
        <input type="hidden" value="{!! $id !!}" name="work_planning_id" id="work_planning_id">
        <input type="hidden" id="row_cnt" name="row_cnt" value="1">
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

                <div class="detail_class" style="display:none;">
                    {!! Form::radio('report_delay','Late in / Early Go',false,['id' => 'report_delay','onchange' => 'displayTextArea("Late in / Early Go")']) !!}
                    {!! Form::label('Late in / Early Go') !!} &nbsp;

                    {!! Form::radio('report_delay','Half Day',false,['id' => 'report_delay','onchange' => 'displayTextArea("Half Day")']) !!}
                    {!! Form::label('Half Day') !!} &nbsp;

                    {!! Form::radio('report_delay','There is delay of Sending Report',false,['id' => 'report_delay','onchange' => 'displayTextArea("There is delay of Sending Report")']) !!}
                    {!! Form::label('There is delay of Sending Report') !!}&nbsp;

                    {!! Form::radio('report_delay','Others',false,['id' => 'report_delay','onchange' => 'displayTextArea("Others")']) !!}
                    {!! Form::label('Others') !!}

                    <div class="form-group report_delay_conent_cls" style="display:none;">
                        <br/><strong>&nbsp;Add Description :</strong>
                        {!! Form::textarea('report_delay_content', null, array('id' => 'report_delay_content','placeholder' => 'Add Text','class' => 'form-control','rows' => '5')) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer first_footer">
                <button type="button" class="btn btn-primary" onclick="displaybuttons();">Yes
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            <div class="modal-footer second_footer" style="display:none;">
                <button type="button" class="btn btn-primary" onclick="submitform();">OK
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->

{!! Form::close() !!}

@section('customscripts')
<script type="text/javascript">

    $(document).ready(function() {

        $("#work_type").select2();

        var action = $("#action").val();

        if(action == 'add') {

            for(j = 1; j <= 5; j++) {

                $("#projected_time_"+j).select2({width:"130px"});
                //$("#remarks_"+j).wysihtml5();
            }

            checkTime();
            jQuery(document).on('focus', '.select2', function() {
                jQuery(this).siblings('select').select2('open');
            });
        }

        var work_planning_id = $("#work_planning_id").val();

        if(action == "edit") {
            loadDetails(work_planning_id);
        }
    });

    function setRemainTime(value) {

        // For calculate total & actual working hours

        var row_cnt = $("#row_cnt").val();
        var projected_time_array = [];

        for(j = 1; j < row_cnt; j++) {
                
            var projected_time = $("#projected_time_"+j).val();
            projected_time_array.push(projected_time);
        }

        const sum = projected_time_array.reduce((acc, time) => acc.add(moment.duration(time)), moment.duration());

        var final_working_hours = [Math.floor(sum.asHours()), sum.minutes(), sum.seconds()].join(':');

        var user_total_hours = $("#user_total_hours").val();

        var time_start = new Date();
        var time_end = new Date();
        var value_start = final_working_hours.split(':');
        var value_end = user_total_hours.split(':');

        time_start.setHours(value_start[0], value_start[1], value_start[2], 0)
        time_end.setHours(value_end[0], value_end[1], value_end[2], 0)

        if(time_start > time_end) {

            alert("Your Total Working Hours are : " + user_total_hours);
            $('#projected_time_'+value).val('0').change();
        }

        // Set remain time difference
        var action = $("#action").val();

        if(action == "add") {

            /*if(value == 1) {

                var get_time = $("#projected_time_"+value).val();
                get_time = get_time + ":00";

                var new_date_1 = "Aug 1, 2021 " + get_time;
                var date1 = new Date(new_date_1);

                var user_total_hours = $("#user_total_hours").val();

                var new_date_2 = "Aug 1, 2021 " + user_total_hours;
                var date2 = new Date(new_date_2);

                var res = Math.abs(date2 - date1) / 1000;
                var hours = Math.floor(res / 3600) % 24;
                var minutes = Math.floor(res / 60) % 60;

                if(hours == 0) {
                    hours = '00';
                }

                if(minutes == 0) {
                    var remain_time = hours + ":" + minutes + "0:00";
                }
                else {
                    var remain_time = hours + ":" + minutes + ":00";
                }

                $("#remaining_time").val(remain_time);
            }
            else {

                var actual_remain_time = $("#remaining_time").val();
                actual_remain_time = actual_remain_time + ":00";

                var new_date = "Aug 1, 2021 " + actual_remain_time;
                var date1 = new Date(new_date);

                var get_time = $("#projected_time_"+value).val();

                var new_date_2 = "Aug 1, 2021 " + get_time;
                var date2 = new Date(new_date_2);

                var res = Math.abs(date2 - date1) / 1000;
                var hours = Math.floor(res / 3600) % 24;
                var minutes = Math.floor(res / 60) % 60;

                if(hours == 0) {
                    hours = '00';
                }

                if(minutes == 0) {
                    var remain_time = hours + ":" + minutes + "0:00";
                }
                else {
                    var remain_time = hours + ":" + minutes + ":00";
                }

                $("#remaining_time").val(remain_time);
            }*/

            var row_cnt = $("#row_cnt").val();
            var projected_time_array = [];

            for(j = 1; j < row_cnt; j++) {
                
                var projected_time = $("#projected_time_"+j).val();
                projected_time_array.push(projected_time);
            }

            const sum = projected_time_array.reduce((acc, time) => acc.add(moment.duration(time)), moment.duration());

            var final_working_hours = [Math.floor(sum.asHours()), sum.minutes(), sum.seconds()].join(':');

            var new_date_1 = "Aug 1, 2021 " + final_working_hours;
            var date1 = new Date(new_date_1);

            var user_total_hours = $("#user_total_hours").val();
            var new_date_2 = "Aug 1, 2021 " + user_total_hours;
            var date2 = new Date(new_date_2);

            var res = Math.abs(date2 - date1) / 1000;
            var hours = Math.floor(res / 3600) % 24;
            var minutes = Math.floor(res / 60) % 60;

            if(hours == 0) {
                hours = '00';
            }

            if(minutes == 0) {
                var remain_time = hours + ":" + "00:00";
            }
            else {
                var remain_time = hours + ":" + minutes + ":00";
            }

            $("#remaining_time").val(remain_time);
        }

        if(action == "edit") {

            var row_cnt = $("#row_cnt").val();
            var projected_time_array = [];

            for(j = 1; j < row_cnt; j++) {
                
                var projected_time = $("#projected_time_"+j).val();
                projected_time_array.push(projected_time);
            }

            const sum = projected_time_array.reduce((acc, time) => acc.add(moment.duration(time)), moment.duration());

            var final_working_hours = [Math.floor(sum.asHours()), sum.minutes(), sum.seconds()].join(':');

            var new_date_1 = "Aug 1, 2021 " + final_working_hours;
            var date1 = new Date(new_date_1);

            var user_total_hours = $("#user_total_hours").val();
            var new_date_2 = "Aug 1, 2021 " + user_total_hours;
            var date2 = new Date(new_date_2);

            var res = Math.abs(date2 - date1) / 1000;
            var hours = Math.floor(res / 3600) % 24;
            var minutes = Math.floor(res / 60) % 60;

            if(hours == 0) {
                hours = '00';
            }

            if(minutes == 0) {
                var remain_time = hours + ":" + "00:00";
            }
            else {
                var remain_time = hours + ":" + minutes + ":00";
            }

            $("#remaining_time").val(remain_time);

            // For calculate total & actual working hours

            var user_total_hours = $("#user_total_hours").val();

            var time_start = new Date();
            var time_end = new Date();
            var value_start = final_working_hours.split(':');
            var value_end = user_total_hours.split(':');

            time_start.setHours(value_start[0], value_start[1], value_start[2], 0)
            time_end.setHours(value_end[0], value_end[1], value_end[2], 0)

            if(time_start > time_end) {

                alert("Your Total Working Hours are : " + user_total_hours);
                $("#remaining_time").val('00:00:00');
            }
        }

        var get_remain_time = $("#remaining_time").val();

        if(get_remain_time == '00:00:00') {

            document.getElementById('remaining_time').style.backgroundColor = '#B0E0E6';
            document.getElementById('remaining_time').style.color = 'Black';
            document.getElementById("add_row").disabled = true;
        }
        else {

            document.getElementById('remaining_time').style.backgroundColor = 'white';
            document.getElementById('remaining_time').style.color = 'Red';
            document.getElementById("add_row").disabled = false;
        }
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

            cell3.innerHTML =  '<td style="border:1px solid black;"><select class="form-control" name="projected_time[]" id="projected_time_'+row_cnt+'" onchange="setRemainTime('+row_cnt+')"><option value="" disabled selected>Select Time</option></select> <br/><br/><select class="form-control" name="actual_time[]" id="actual_time_'+row_cnt+'" readonly=true><option value="" disabled selected>Select Time</option></select></td>';
        }
        else {

            cell3.innerHTML = '<td style="border:1px solid black;"><select class="form-control" name="projected_time[]" id="projected_time_'+row_cnt+'" onchange="setRemainTime('+row_cnt+')"><option value="" disabled selected>Select Time</option></select> <br/><br/><select class="form-control" name="actual_time[]" id="actual_time_'+row_cnt+'"><option value="" disabled selected>Select Time</option></select></td>';
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
        //$("#remarks_"+row_cnt).wysihtml5();

        if(action == "add") {

            document.getElementById("actual_time_"+row_cnt).disabled = true;
        }
        else {
            $("#actual_time_"+row_cnt).select2();
        }

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

        var projected_time_array = [];

        for(j = 1; j < row_cnt_new; j++) {
                
            var projected_time = $("#projected_time_"+j).val();
            projected_time_array.push(projected_time);
        }

        const sum = projected_time_array.reduce((acc, time) => acc.add(moment.duration(time)), moment.duration());

        var final_working_hours = [Math.floor(sum.asHours()), sum.minutes(), sum.seconds()].join(':');
        var new_date_1 = "Aug 1, 2021 " + final_working_hours;
        var date1 = new Date(new_date_1);

        var user_total_hours = $("#user_total_hours").val();
        var new_date_2 = "Aug 1, 2021 " + user_total_hours;
        var date2 = new Date(new_date_2);

        var res = Math.abs(date2 - date1) / 1000;
        var hours = Math.floor(res / 3600) % 24;
        var minutes = Math.floor(res / 60) % 60;

        if(hours == 0) {
            hours = '00';
        }

        if(minutes == 0) {
            var remain_time = hours + ":" + "00:00";
        }
        else {
            var remain_time = hours + ":" + minutes + ":00";
        }

        $("#remaining_time").val(remain_time);

        if(remain_time == '00:00:00') {

            document.getElementById('remaining_time').style.backgroundColor = '#B0E0E6';
            document.getElementById('remaining_time').style.color = 'Black';
            document.getElementById("add_row").disabled = true;
        }
        else {

            document.getElementById('remaining_time').style.backgroundColor = 'white';
            document.getElementById('remaining_time').style.color = 'Red';
            document.getElementById("add_row").disabled = false;
        }
    }

    function loadDetails(work_planning_id) {

        $.ajax({

            type: 'GET',
            url: '/work-planning/getlist',
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

                        var time_array_1 = <?php echo json_encode($time_array); ?>;
                        var row_cnt = $("#row_cnt").val();

                        var table = document.getElementById("work_planning_table");
                        var row = table.insertRow(-1);
                        row.className = 'row_'+row_cnt+'';
                        row.style.border = '1px solid black';

                        var cell1 = row.insertCell(0);
                        cell1.style.border = '1px solid black';
                        cell1.innerHTML = row_cnt;

                        var cell2 = row.insertCell(1);
                        cell2.style.border = '1px solid black';
                        cell2.innerHTML = '<td style="border:1px solid black;"><textarea name="task[]" placeholder="Task" id="task_'+row_cnt+'" class="form-control" rows="3">'+task+'</textarea></td>';

                        var cell3 = row.insertCell(2);
                        cell3.style.border = '1px solid black';
                        cell3.innerHTML = '<td style="border:1px solid black;"><select class="form-control" name="projected_time[]" id="projected_time_'+row_cnt+'" onchange="setRemainTime('+row_cnt+')"><option value="" disabled selected>Select Time</option></select> <br/><br/><select class="form-control" name="actual_time[]" id="actual_time_'+row_cnt+'"><option value="" disabled selected>Select Time</option> </select></td>';

                        var cell4 = row.insertCell(3);
                        cell4.style.border = '1px solid black';
                        cell4.innerHTML = '<td style="border:1px solid black;"><textarea name="remarks[]" placeholder="Remarks" id="remarks_'+row_cnt+'" class="form-control" rows="5">'+remarks+'</textarea></td>';

                        if(rm_hr_remarks == null) {

                            rm_hr_remarks = '';
                        }

                        var cell5 = row.insertCell(4);
                        cell5.style.border = '1px solid black';
                        cell5.innerHTML = '<td style="border:1px solid black;"><textarea name="rm_hr_remarks[]" placeholder="RM / HR Remarks" id="rm_hr_remarks_'+row_cnt+'" class="form-control" rows="5" disabled>'+rm_hr_remarks+'</textarea></td>';

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

                        //$("#remarks_"+row_cnt).wysihtml5();
                        //$("#rm_hr_remarks_"+row_cnt).wysihtml5();
                        
                        var row_cnt_new = parseInt(row_cnt)+1;
                        $("#row_cnt").val(row_cnt_new);

                        if(row_cnt_new < 5) {
                            document.getElementById("remove_row").disabled = true;
                        }
                    }
                }

                var get_remain_time = $("#remaining_time").val();

                if(get_remain_time == '00:00:00') {

                    document.getElementById('remaining_time').style.backgroundColor = '#B0E0E6';
                    document.getElementById('remaining_time').style.color = 'Black';
                    document.getElementById("add_row").disabled = true;
                }
                else {

                    document.getElementById('remaining_time').style.backgroundColor = 'white';
                    document.getElementById('remaining_time').style.color = 'Red';
                    document.getElementById("add_row").disabled = false;
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

        if(dd2 > dd1) {

            $("#alertModal").modal('show');
            return false;
        }
        
        return true;
    }

    function displaybuttons() {

        $(".detail_class").show();
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

    function hoursValidation() {

        // For calculate actual working hours added by user

        var row_cnt = $("#row_cnt").val();
        var projected_time_array = [];

        for(j = 1; j < row_cnt; j++) {
                
            var projected_time = $("#projected_time_"+j).val();
            projected_time_array.push(projected_time);
        }

        const sum = projected_time_array.reduce((acc, time) => acc.add(moment.duration(time)), moment.duration());

        var final_working_hours = [Math.floor(sum.asHours()), sum.minutes(), sum.seconds()].join(':');

        var total_time = final_working_hours.split(':');

        // Set Hours
        if(total_time[0] == '0') {
            var hours = '00';
        }
        else {

            if(total_time[0] > 10) {
                var hours = total_time[0];
            }
            else {
                var hours = "0"+total_time[0];
            }
        }

        // Set Minutes
        if(total_time[1] == '0') {
            var minutes = '00';
        }
        else {
            var minutes = total_time[1];
        }

        // Set Seconds
        if(total_time[2] == '0') {
            var seconds = '00';
        }
        else {
            var seconds = total_time[2];
        }

        var final_added_time = hours+":"+minutes+":"+seconds;

        var user_total_hours = $("#user_total_hours").val();
        var user_half_day_hours = $("#user_half_day_hours").val();
        var early_late_in_time = $("#early_late_in_time").val();

        if(user_total_hours == final_added_time || user_half_day_hours == final_added_time || early_late_in_time == final_added_time) {
        }
        else {

            alert("Please Add Proper Report.");
            return false;
        }
    }
</script>
@endsection