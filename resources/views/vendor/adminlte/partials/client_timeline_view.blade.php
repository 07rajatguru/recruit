<a data-toggle="modal" href="#modal-timeline-{!! $data['id'] !!}" class="fa fa-times-circle-o" title="Timeline" style="margin:2px;"></a>
<div id="modal-timeline-{!! $data['id'] !!}" class="modal text-left fade">
	<div class="modal-dialog">
        <div class="modal-content">

        	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">{{ $data['full_name'] }}</h3>
            </div>

            <div class="modal-body">
                <table id="timeline_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: center;"><b>Username</b></td>
                        <td style="text-align: center;"><b>From Date</b></td>
                        <td style="text-align: center;"><b>To Date</b></td>
                        <td style="text-align: center;"><b>Days</b></td>
                    </tr>

                    @if(isset($days_array) && sizeof($days_array) > 0)
                        @foreach($days_array as $key => $value)
                            <tr>
                                @if($value['user_id'] == 0)
                                    <td style="text-align: center;">Yet to Assign</td>
                                @else
                                    <td style="text-align: center;">{{ $value['user_name'] }}
                                    </td>
                                @endif

                                <td style="text-align: center;">{{ $value['from_date'] }}</td>

                                @if($value['to_date'] == '-')
                                    <td style="text-align: center;">Present</td>
                                @else
                                    <td style="text-align: center;">{{ $value['to_date'] }}</td>
                                @endif

                                @if($value['days'] == '-')
                                    <?php
                                        if($value['to_date'] == $value['from_date']){
                                            $diff_in_days = '1';
                                        }
                                        else{
                                            $today_date = date('d-m-Y');
                                            $to = strtotime($today_date);
                                            $from = strtotime($value['from_date']);
                                            $diff_in_days = ($to - $from)/60/60/24;
                                        }
                                    ?>
                                    <td style="text-align: center;">{{ $diff_in_days }}</td>
                                @else
                                    <td style="text-align: center;">{{ $value['days'] }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>

            <div class="modal-footer">
                <!-- <button type="submit" class="btn btn-primary">Ok</button> -->
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
           
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
