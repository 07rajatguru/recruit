@extends('adminlte::page')

@section('title', 'Employee Self Service')

@section('content_header')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Employee Self Service</h2>
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
            <div class="small-box bg-red" style="border-radius:20%;">
                <div class="inner">
                    <h3>{{ $pending_work_planning_count or '0' }}</h3>
                    <p>My Pending Work Planning</p>
                </div>
                <div class="icon">
                    <i class="fa fa-files-o"></i>
                </div>
                <a href="{{ route('workplanning.pending') }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-green" style="border-radius:20%;">
                <div class="inner">
                    <h3>{{ $leave_count or '0' }}</h3>
                    <p>My Applied Leave</p>
                </div>
                <div class="icon">
                    <i class="fa fa-leanpub"></i>
                </div>
                <a href="{{ route('applied.leave') }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-maroon" style="border-radius:20%;">
                <div class="inner">
                    <h3>{{ $present_days or '0' }}</h3>
                    <p>My Attendance</p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar"></i>
                </div>
                <a href="/self-user-attendance" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-aqua" style="border-radius:20%;">
                <div class="inner">
                    <h3>{{ $earlygo_latein_count or '0' }}</h3>
                    <p>My Late In / Early Go Request in this month</p>
                </div>
                <div class="icon">
                    <i class="fa fa-file-text-o"></i>
                </div>
                <a href="{{ route('late.early') }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-purple" style="border-radius:20%;">
                <div class="inner">
                    <h3>{{ $optional_holidays_count or '0' }}</h3>
                    <p>My Optional holidays in this month</p>
                </div>
                <div class="icon">
                    <i class="fa fa-list-alt"></i>
                </div>
                <a href="{{ route('optional.holidays') }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-yellow" style="border-radius:20%;">
                <div class="inner">
                    <h3>{{ $fixed_holidays_count or '0' }}</h3>
                    <p>My Fixed holidays in this month</p>
                </div>
                <div class="icon">
                    <i class="fa fa-list-alt"></i>
                </div>
                <a href="{{ route('fixed.holidays') }}" class="small-box-footer" target="_blank">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Employee Birthday / Anniversary in this month</h3>

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
                                <th width="5px" style="border: 1px solid #00c0ef;">Sr. No.</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Employye Name</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">List of Optional Holidays in This Year</h3>

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
                                <th width="100px" style="border: 1px solid #00c0ef;">Title</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Date</th>
                            </tr>
                            </thead>
                            @if(isset($optional_holiday_details) && sizeof($optional_holiday_details) > 0)
                            <?php $i = 0; ?>
                                <tbody>
                                    @foreach($optional_holiday_details as $holidays)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">{{ ++$i }}</td>
                                            <td style="border: 1px solid #00c0ef;">{{ $holidays['title'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">{{ $holidays['from_date'] }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
                    <h3 class="box-title">List of Fixed Holidays in This Year</h3>

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
                                <th width="100px" style="border: 1px solid #00c0ef;">Title</th>
                                <th width="100px" style="border: 1px solid #00c0ef;">Date</th>
                            </tr>
                            </thead>
                            @if(isset($fixed_holiday_details) && sizeof($fixed_holiday_details) > 0)
                            <?php $i = 0; ?>
                                <tbody>
                                    @foreach($fixed_holiday_details as $holidays)
                                        <tr>
                                            <td style="border: 1px solid #00c0ef;">{{ ++$i }}</td>
                                            <td style="border: 1px solid #00c0ef;">{{ $holidays['title'] }} </td>
                                            <td style="border: 1px solid #00c0ef;">{{ $holidays['from_date'] }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                    <div class="box-footer clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('customscripts')
    <script>
        jQuery(document).ready(function () {
        });
    </script>
@stop