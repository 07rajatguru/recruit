@extends('adminlte::page')

@section('title', 'Daily Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Daily Report</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('dailyreport.create') }}"> Add Daily Report</a>
            </div>

            {!! Form::open(array('route' => 'dailyreport.reportMailToandCC','files' => true,'method'=>'POST')) !!}

                <div class="pull-right" style="padding-right: 1%">
                    <button type="submit" class="btn btn-success"> Send Mail</button>
                </div>

               {{-- <div class="pull-right" style="padding-right: 1%">
                    {!! Form::text('toDate', isset($toDate) ? $toDate : null, array('id'=>'toDate','placeholder' => 'To Date','class' => 'form-control date' )) !!}
                </div>

                <div class="pull-right" style="padding-right: 1%">
                    {!! Form::text('fromDate', isset($fromDate) ? $fromDate : null, array('id'=>'fromDate','placeholder' => 'From Date','class' => 'form-control date' )) !!}
                </div>--}}

            {!! Form::close() !!}
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="dailyreport_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Created by</th>
                <th>Client</th>
                <th>Location</th>
                <th>Report Date</th>
                <th width="280px">Action</th>
            </tr>
        </thead>

        <?php $i=0; ?>
        <tbody>
        @foreach ($dailyReports as $dailyReport)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $dailyReport->user_name or '' }}</td>
                <td>{{ $dailyReport->client_name or '' }}</td>
                <td>{{ $dailyReport->location or ''}}</td>
                <td>{{ $dailyReport->report_date or ''}}</td>

                <td>
                    <a class="btn btn-info" href="{{ route('dailyreport.show',$dailyReport->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('dailyreport.edit',$dailyReport->id) }}">Edit</a>
                    @include('adminlte::partials.deleteModal', ['data' => $dailyReport, 'name' => 'dailyreport','display_name'=>'Daily Report'])
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script>
        $(document).ready(function(){
            $(".date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });

            var table = jQuery('#dailyreport_table').DataTable({
                responsive: true,
                stateSave : true
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection