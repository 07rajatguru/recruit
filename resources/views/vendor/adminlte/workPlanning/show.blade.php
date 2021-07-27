@extends('adminlte::page')

@section('title', 'Work Planning')

@section('content_header')
    <h1></h1>
@stop

@section('content')

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{ $work_planning['added_date'] }}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('workplanning.index') }}">Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6"></div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th>User Name :</th>
                        <td>{{ $work_planning['added_by'] }}</td>
                        <th>Work Location :</th>
                        <td>{{ $work_planning['work_type'] }}</td>
                    </tr>
                    <tr>
                        <th>Logged-in Time :</th>
                        <td>{{ $work_planning['loggedin_time'] }}</td>
                        <th>Logged-out Time :</th>
                        <td>{{ $work_planning['loggedout_time'] }}</td>
                    </tr>
                    <tr>
                        <th>Work Planning Time :</th>
                        <td>{{ $work_planning['work_planning_time'] }}</td>
                        <th>Status Time :</th>
                        <td>{{ $work_planning['work_planning_status_time'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if(isset($work_planning_list) && sizeof($work_planning_list) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%" style="border:1px solid black;">Sr No.</th>
                            <th width="35%" style="border:1px solid black;">Description</th>
                            <th width="10%" style="border:1px solid black;">Projected Time</th>
                            <th width="10%" style="border:1px solid black;">Actual Time </th>
                            <th style="border:1px solid black;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; ?>
                        @foreach($work_planning_list as $key=>$value)
                            <tr>
                                <td style="border:1px solid black;text-align: center;">{{ $i++ }}</td>
                                <td style="border:1px solid black;">{{ $value['description'] }}</td>
                                <td style="border:1px solid black;text-align: center;">{{ $value['projected_time'] }}</td>
                                <td style="border:1px solid black;text-align: center;">{{ $value['actual_time'] }}</td>
                                <td style="border:1px solid black;">{{ $value['remarks'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection