@extends('adminlte::page')

@section('title', 'HR Employee Service')

@section('content_header')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="col-md-12">
                <div class="col-md-4"><h2>HR Employee Service</h2></div>
            </div>
        </div>

        <div class="col-lg-12 margin-tb">
            <div class="col-md-12">
                <div class="col-md-1">
                    <select class="form-control" name="month" id="month">
                        @foreach($month_array as $key=>$value)
                            <option value={{ $key }} @if($key==$month) selected="selected" @endif>{{ $value}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <select class="form-control" name="year" id="year">
                        @foreach($year_array as $key=>$value)
                            <option value={{ $key }} @if($key==$year) selected="selected" @endif>{{ $value}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <input class="btn btn-primary btn-block" type="button" value="Filter" name ="filter" id="filter" onClick="filter_data()" style="width:90px;"/>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    @if($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-blue" style="border-radius:100%;">
                <div class="inner">
                    <h3><center>{{ $pending_work_planning_count or '0' }}</center></h3>
                    <h5><center>Pending Work Planning</center></h5>
                </div>
                <div class="icon"><i class="fa fa-files-o"></i></div>
                <a href="work-planning/pending/1/{{ $month }}/{{ $year }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-yellow" style="border-radius:100%;">
                <div class="inner">
                    <h3><center>{{ $leave_count or '0' }}</center></h3>
                    <h5><center>Applied Leave</center></h5>
                </div>
                <div class="icon"><i class="fa fa-leanpub"></i></div>
                <a href="applied-leave/1/{{ $month }}/{{ $year }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-red" style="border-radius:100%;">
                <div class="inner">
                    <h3><center>0</center></h3>
                    <h5><center>Attendance</center></h5>
                </div>
                <div class="icon"><i class="fa fa-calendar"></i></div>
                <a href="/users-attendance/adler/{{ $month }}/{{ $year }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-green" style="border-radius:100%;">
                <div class="inner">
                    <h3><center>{{ $earlygo_latein_count or '0' }}</center></h3>
                    <h5><center>Late In / Early Go Request in this month</center></h5>
                </div>
                <div class="icon"><i class="fa fa-file-text-o"></i></div>
                <a href="late-in-early-go-request/1/{{ $month }}/{{ $year }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-purple" style="border-radius:100%;">
                <div class="inner">
                    <h3><center>{{ $work_from_count or '0' }}</center></h3>
                    <h5><center>Work From Home Request in this month</center></h5>
                </div>
                <div class="icon"><i class="fa fa-list-alt"></i></div>
                <a href="work-from-home-request/1/{{ $month }}/{{ $year }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-maroon" style="border-radius:100%;">
                <div class="inner">
                    <h3><center>{{ $holidays_count or '0' }}</center></h3>
                    <h5><center>Holidays in this month</center></h5>
                </div>
                <div class="icon"><i class="fa fa-list-alt"></i></div>
                <a href="holidays/1/{{ $month }}/{{ $year }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Employee's Work Anniversary in This Month</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin" style="border: 1px solid #00c0ef;">
                            <thead>
                            <tr>
                                <th width="5px" style="border: 1px solid #00c0ef;text-align: center;">Sr. No.</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Employee Name</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Date</th>
                            </tr>
                            </thead>
                            @if(isset($work_anniversary_dates) && sizeof($work_anniversary_dates) > 0)
                            <?php $work_anniversary_i = 0; ?>
                                <tbody>
                                    @foreach($work_anniversary_dates as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;text-align: center;">
                                            {{ ++$work_anniversary_i }}</td>
                                            <td style="border: 1px solid #00c0ef;">{{ $key }}</td>
                                            <td style="border: 1px solid #00c0ef;">{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <tbody><tr><td colspan="3">No Data Found.</td></tr></tbody>
                            @endif
                        </table>
                    </div>
                    <div class="box-footer clearfix"></div>
                </div>
            </div>
        </div>

         <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">List of Applied Late In / Early Go Requests</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                            <i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin" style="border: 1px solid #00c0ef;">
                            <thead>
                            <tr>
                                <th width="11%" style="border: 1px solid #00c0ef;">Sr. No.</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">User Name
                                </th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Subject</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Date</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Type</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">View Details</th>
                            </tr>
                            </thead>

                            <?php $latein_earlygo_i = 0; ?>
                            @if(isset($latein_earlygo_data) && sizeof($latein_earlygo_data) > 0)
                                <tbody>
                                    @foreach($latein_earlygo_data as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ ++$latein_earlygo_i }}</td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['user_name'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['subject'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['leave_type'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                                <a title="View Details" href="{{ route('late-early.reply',$value['id']) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                            @if(isset($team_latein_earlygo_details) && sizeof($team_latein_earlygo_details) > 0)
                                <tbody>
                                    @foreach($team_latein_earlygo_details as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ ++$latein_earlygo_i }}</td>
                                            <td style="border: 1px solid #00c0ef;background-color:#C4D79B;">{{ $value['user_name'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['subject'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['leave_type'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                                <a title="View Details" href="{{ route('late-early.reply',$value['id']) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                            @if(isset($all_latein_earlygo_details) && sizeof($all_latein_earlygo_details) > 0)
                                <tbody>
                                    @foreach($all_latein_earlygo_details as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ ++$latein_earlygo_i }}</td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['user_name'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['subject'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['leave_type'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                                <a title="View Details" href="{{ route('late-early.reply',$value['id']) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                            @if((isset($team_latein_earlygo_details) && sizeof($team_latein_earlygo_details) > 0) || (isset($all_latein_earlygo_details) && sizeof($all_latein_earlygo_details) > 0) || (isset($latein_earlygo_data) && sizeof($latein_earlygo_data) > 0))

                            @else
                                <tbody><tr><td colspan="6">No Data Found.</td></tr></tbody>
                            @endif
                        </table>
                    </div>

                    @if(isset($earlygo_latein_count) && $earlygo_latein_count > 5)
                        <div class="box-footer text-center">
                            <a href="/late-in-early-go-request/1/{{ $month }}/{{ $year }}" target="_blank">View All Requests</a>
                        </div>
                    @else
                        <div class="box-footer clearfix"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Employee's Birthday in This Month</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin" style="border: 1px solid #00c0ef;">
                            <thead>
                            <tr>
                                <th width="5px" style="border: 1px solid #00c0ef;text-align: center;">Sr. No.</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Employee Name</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Date</th>
                            </tr>
                            </thead>
                            @if(isset($birthday_dates) && sizeof($birthday_dates) > 0)
                            <?php $birthday_dates_i = 0; ?>
                                <tbody>
                                    @foreach($birthday_dates as $key1 => $value1)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;text-align: center;">
                                            {{ ++$birthday_dates_i }}</td>
                                            <td style="border: 1px solid #00c0ef;">{{ $key1 }}</td>
                                            <td style="border: 1px solid #00c0ef;">{{ $value1 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <tbody><tr><td colspan="3">No Data Found.</td></tr></tbody>
                            @endif
                        </table>
                    </div>
                    <div class="box-footer clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">List of Applied Leaves</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin" style="border: 1px solid #00c0ef;">
                            <thead>
                            <tr>
                                <th width="11%" style="border: 1px solid #00c0ef;">Sr. No.</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">User Name
                                </th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Subject</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">From Date
                                </th>
                                <th width="100px" style="border: 1px solid #00c0ef;">To Date</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">View Details</th>
                            </tr>
                            </thead>

                            <?php $leave_i = 0; ?>
                            @if(isset($leave_data) && sizeof($leave_data) > 0)
                                <tbody>
                                    @foreach($leave_data as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">{{ ++$leave_i }}</td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['user_name'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['subject'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['from_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['to_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                                <a title="View Details" href="{{ route('leave.reply',$value['id']) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                            @if(isset($team_leave_details) && sizeof($team_leave_details) > 0)
                                <tbody>
                                    @foreach($team_leave_details as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">{{ ++$leave_i }}</td>
                                            <td style="border: 1px solid #00c0ef;background-color:#C4D79B;">{{ $value['user_name'] }}</td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['subject'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['from_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['to_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                                <a title="View Details" href="{{ route('leave.reply',$value['id']) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                            @if(isset($all_leave_details) && sizeof($all_leave_details) > 0)
                                <tbody>
                                    @foreach($all_leave_details as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">{{ ++$leave_i }}</td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['user_name'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['subject'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['from_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['to_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                                <a title="View Details" href="{{ route('leave.reply',$value['id']) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                            @if((isset($team_leave_details) && sizeof($team_leave_details) > 0) || (isset($all_leave_details) && sizeof($all_leave_details) > 0) || (isset($leave_data) && sizeof($leave_data) > 0))

                            @else
                                <tbody><tr><td colspan="6">No Data Found.</td></tr></tbody>
                            @endif
                        </table>
                    </div>

                    @if(isset($leave_count) && $leave_count > 5)
                        <div class="box-footer text-center">
                            <a href="/applied-leave/1/{{ $month }}/{{ $year }}" target="_blank">View All Requests</a>
                        </div>
                    @else
                        <div class="box-footer clearfix"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">List of Holidays in This Year</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin" style="border: 1px solid #00c0ef;">
                            <thead>
                            <tr>
                                <th width="11%" style="border: 1px solid #00c0ef;">Sr. No.</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Type</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Title</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Date</th>
                            </tr>
                            </thead>
                            @if(isset($holidays) && sizeof($holidays) > 0)
                            <?php $holidays_i = 0; ?>
                                <tbody>
                                    @foreach($holidays as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">{{ ++$holidays_i }}
                                            </td>
                                            <td style="border: 1px solid #00c0ef;">{{ $value['type'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">{{ $value['title'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">{{ $value['from_date'] }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <tbody><tr><td colspan="3">No Data Found.</td></tr></tbody>
                            @endif
                        </table>
                    </div>
                    <div class="box-footer clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">List of Applied Work From Home Requests</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin" style="border: 1px solid #00c0ef;">
                            <thead>
                            <tr>
                                <th width="11%" style="border: 1px solid #00c0ef;">Sr. No.</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">User Name
                                </th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Subject</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">From Date
                                </th>
                                <th width="100px" style="border: 1px solid #00c0ef;">To Date</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">View Details</th>
                            </tr>
                            </thead>
                            <?php $wfh_i = 0; ?>
                            @if(isset($wfh_data) && sizeof($wfh_data) > 0)
                                <tbody>
                                    @foreach($wfh_data as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">{{ ++$wfh_i }}</td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['user_name'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['subject'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['from_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['to_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                                <a title="View Details" href="{{ route('workfromhome.show',$value['id']) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                            @if(isset($team_wfh_details) && sizeof($team_wfh_details) > 0)
                                <tbody>
                                    @foreach($team_wfh_details as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">{{ ++$wfh_i }}</td>
                                            <td style="border: 1px solid #00c0ef;background-color:#C4D79B;">{{ $value['user_name'] }}</td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['subject'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['from_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['to_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                                <a title="View Details" href="{{ route('workfromhome.show',$value['id']) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                            @if(isset($all_wfh_details) && sizeof($all_wfh_details) > 0)
                                <tbody>
                                    @foreach($all_wfh_details as $key => $value)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">{{ ++$wfh_i }}</td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['user_name'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['subject'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['from_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                            {{ $value['to_date'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">
                                                <a title="View Details" href="{{ route('workfromhome.show',$value['id']) }}" target="_blank">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                            @if((isset($team_wfh_details) && sizeof($team_wfh_details) > 0) || (isset($all_wfh_details) && sizeof($all_wfh_details) > 0) || (isset($wfh_data) && sizeof($wfh_data) > 0))

                            @else
                                <tbody><tr><td colspan="6">No Data Found.</td></tr></tbody>
                            @endif
                        </table>
                    </div>

                    @if(isset($work_from_count) && $work_from_count > 5)
                        <div class="box-footer text-center">
                            <a href="/work-from-home-request/1/{{ $month }}/{{ $year }}" target="_blank">View All Requests</a>
                        </div>
                    @else
                        <div class="box-footer clearfix"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('customscripts')
    <script>
        jQuery(document).ready(function () {

            $("#month").select2({width : '90px'});
            $("#year").select2({width : '90px'});
        });

        function filter_data() {

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();

            var url = '/hr-employee-service';

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="month" value="'+month+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@stop