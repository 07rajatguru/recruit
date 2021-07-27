@section('customs_css')
<style>
    .error
    {
        color:#f56954 !important;
    }
</style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
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
                            {!! Form::text('work_planning_status_time',$loggedout_time, array('id' => 'work_planning_status_time','class' => 'form-control','tabindex' => '5','readonly' => 'true')) !!}
                        </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4">
                        <div class="form-group">
                            <strong>Remaining Time : </strong>
                            {!! Form::text('remaining_time',null, array('id' => 'remaining_time','class' => 'form-control','tabindex' => '6','readonly' => 'true')) !!}
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
                               <th style="border:1px solid black;">Description</th>
                               <th style="border:1px solid black;">Projected Time</th>
                               <th style="border:1px solid black;">Actual Time </th>
                               <th style="border:1px solid black;">Remarks</th>
                            </tr>
                        </thead>

                        <tbody>    
                            <?php $tabindex = 6; ?>
                            @for($i=1; $i<=5; $i++)
                                <tr class="row_{{ $i }}" style="border:1px solid black;">
                                    <td style="border:1px solid black;text-align: center;">{{ $i }}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!!Form::textarea('description[]',null, array('placeholder' => 'Description','id' => 'description_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'rows' => 3)) !!}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!! Form::text('projected_time[]',null, array('placeholder' => 'Projected Time','id' => 'projected_time_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'onfocusout'=>'setRemainTime('.$i.')')) !!}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!! Form::text('actual_time[]',null, array('placeholder' => 'Actual Time','id' => 'actual_time_'.$i,'class' => 'form-control','tabindex' => $tabindex++)) !!}
                                    </td>

                                    <td style="border:1px solid black;">
                                        {!!Form::textarea('remarks[]',null, array('placeholder' =>'Remarks','id' => 'remarks_'.$i,'class' => 'form-control','tabindex' => $tabindex++,'rows' => 3)) !!}
                                    </td>
                                </tr>
                            @endfor
                            @for($j=6; $j<=20; $j++)
                                <tr class="row_{{ $j }}" style="border:1px solid black;"></tr>
                            @endfor
                        </tbody>
                    </table>
                @endif
                @if($action == 'edit')
                    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="work_planning_table" style="border:1px solid black;">
                        <thead>
                            <tr style="border:1px solid black;">
                               <th width="5%" style="border:1px solid black;">Sr No.</th>
                               <th style="border:1px solid black;">Description</th>
                               <th style="border:1px solid black;">Projected Time</th>
                               <th style="border:1px solid black;">Actual Time </th>
                               <th style="border:1px solid black;">Remarks</th>
                            </tr>
                        </thead>

                        <tbody>    
                            @for($i=1; $i<=20; $i++)
                                <tr class="row_{{ $i }}" style="border:1px solid black;" style="display:none;">
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                @endif
            </div>
            
            <div style="margin-left:500px;">
                <button type="button" disabled="true" class="btn btn-primary" id="remove_row" onclick="RemoveRow();">Remove</button>
                <button type="button" class="btn btn-primary" onclick="AddRow();">Add</button>
            </div><br/>
        </div>
    </div>
     
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        {!! Form::submit(isset($work_planning_res) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
    </div>

    <input type="hidden" id="action" name="action" value="{{ $action }}">

    @if( $action == 'add')
        <input type="hidden" id="row_cnt" name="row_cnt" value="6">
    @endif

    @if($action == 'edit')
        <input type="hidden" value="{!! $id !!}" name="work_planning_id" id="work_planning_id">
        <input type="hidden" id="row_cnt" name="row_cnt" value="1">
    @endif
</div>

{!! Form::close() !!}

@section('customscripts')
<script type="text/javascript">

    $(document).ready(function() {

        $("#work_type").select2();

        for(j = 1; j <= 5; j++) {

            $("#projected_time_"+j).datetimepicker({
                format: 'H:mm'
            });

            $("#actual_time_"+j).datetimepicker({
                format: 'H:mm'
            }); 
        }

        // automaticaly open the select2 when it gets focus
        jQuery(document).on('focus', '.select2', function() {
            jQuery(this).siblings('select').select2('open');
        });

        var action = {!! json_encode($action) !!};
        var work_planning_id = $("#work_planning_id").val();

        if(action == "edit") {
            loadDetails(work_planning_id);
        }
    });

    function setRemainTime(value) {

        if(value == 1) {

            var get_time = $("#projected_time_"+value).val();
            get_time = get_time + ":00";

            var new_date = "Aug 1, 2021 " + get_time;
            var date1 = new Date(new_date);

            var date2 = new Date( "Aug 1, 2021 08:00:00");

            var res = Math.abs(date2 - date1) / 1000;
            var hours = Math.floor(res / 3600) % 24;
            var minutes = Math.floor(res / 60) % 60;

            if(minutes == 0) {
                var remain_time = hours + ":" + minutes + "0";
            }
            else {
                var remain_time = hours + ":" + minutes;
            }

            $("#remaining_time").val(remain_time);
        }
        else {

            var actual_remain_time = $("#remaining_time").val();
            actual_remain_time = actual_remain_time + ":00";

            var new_date = "Aug 1, 2021 " + actual_remain_time;
            var date1 = new Date(new_date);

            var get_time = $("#projected_time_"+value).val();
            get_time = get_time + ":00";

            var new_date_2 = "Aug 1, 2021 " + get_time;
            var date2 = new Date(new_date_2);

            var res = Math.abs(date2 - date1) / 1000;
            var hours = Math.floor(res / 3600) % 24;

            var minutes = Math.floor(res / 60) % 60;

            if(minutes == 0) {
                var remain_time = hours + ":" + minutes + "0";
            }
            else {
                var remain_time = hours + ":" + minutes;
            }

            $("#remaining_time").val(remain_time);
        }
    }

    function AddRow() {   

        var row_cnt = $("#row_cnt").val();

        var html = '';

        html += '<td style="border:1px solid black;text-align: center;width: 60px;">'+row_cnt+'</td>';

        html += '<td style="border:1px solid black;width: 381px;">';
        html += '<textarea name="description[]" placeholder="Description" id="description_'+row_cnt+'" class="form-control" rows="3"></textarea>';
        html += '</td>';

        html += '<td style="border:1px solid black;width: 200px;">';
        html += '<input type="text" class="form-control" name="projected_time[]" id="projected_time_'+row_cnt+'" placeholder="Projected Time");" onfocusout="setRemainTime("'+row_cnt+'")">';
        html += '</td>';

        html += '<td style="border:1px solid black;width: 200px;">';
        html += '<input type="text" class="form-control" name="actual_time[]" id="actual_time_'+row_cnt+'" placeholder="Actual Time");">';
        html += '</td>';

        html += '<td style="border:1px solid black;width: 377px;">';
        html += '<textarea name="remarks[]" placeholder="Remarks" id="remarks_'+row_cnt+'" class="form-control" rows="3"></textarea>';
        html += '</td>';

        $(".row_"+row_cnt).append(html);

        $("#projected_time_"+row_cnt).datetimepicker({
            format: 'H:mm'
        });

        $("#actual_time_"+row_cnt).datetimepicker({
            format: 'H:mm'
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
                        var description = data[j].description;
                        var projected_time = data[j].projected_time;
                        var actual_time = data[j].actual_time;
                        var remarks = data[j].remarks;

                        var row_cnt = $("#row_cnt").val();

                        var html = '';

                        html += '<td style="border:1px solid black;text-align: center;width: 60px;">'+row_cnt+'</td>';

                        html += '<td style="border:1px solid black;width: 381px;">';
                        html += '<textarea name="description[]" placeholder="Description" id="description_'+row_cnt+'" class="form-control" rows="3">'+description+'</textarea>';
                        html += '</td>';

                        html += '<td style="border:1px solid black;width: 200px;">';
                        html += '<input type="text" class="form-control" name="projected_time[]" id="projected_time_'+row_cnt+'" placeholder="Projected Time");" value="'+projected_time+'" onfocusout="setRemainTime("'+row_cnt+'")">';
                        html += '</td>';

                        html += '<td style="border:1px solid black;width: 200px;">';
                        html += '<input type="text" class="form-control" name="actual_time[]" id="actual_time_'+row_cnt+'" placeholder="Actual Time");" value="'+actual_time+'">';
                        html += '</td>';

                        html += '<td style="border:1px solid black;width: 377px;">';
                        html += '<textarea name="remarks[]" placeholder="Remarks" id="remarks_'+row_cnt+'" class="form-control" rows="3" >'+remarks+'</textarea>';
                        html += '</td>';

                        $(".row_"+row_cnt).append(html);

                        $("#projected_time_"+row_cnt).datetimepicker({
                            format: 'H:mm'
                        });

                        $("#actual_time_"+row_cnt).datetimepicker({
                            format: 'H:mm'
                        }); 

                        var row_cnt_new = parseInt(row_cnt)+1;
                        $("#row_cnt").val(row_cnt_new);
                    }
                }
            }
        });
    }
</script>
@endsection