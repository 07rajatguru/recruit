@extends('adminlte::emails.emailMaster')

@section('title')
    <p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">Daily report from <b>{!! $fromDate !!}</b> of <b>{!! $toDate !!}</b>.</p>
@stop

@section('style')
    <style>
        .table-bordered {
            border: 1px solid #ddd;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #ddd;
        }

        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }
    </style>
@endsection

@section('description')
    <table class="table table-bordered">
        <tr>
            <th>Report Date</th>
            <th>No</th>
            <th>Position Name</th>
            <th>Company</th>
            <th>Location</th>
            <th>Number of CVs sent to TL / Supervisor</th>
            <th>Number of Cvs reached to client after screening</th>
            <th>Created by</th>
            <th>Status</th>
        </tr>

        <?php $i=0; ?>

        @foreach ($dailyReports as $dailyReport)
            <tr>
                <td>{{ $dailyReport->report_date or ''}}</td>
                <td>{{ ++$i }}</td>
                <td>{{ $dailyReport->position_name or '' }}</td>
                <td>{{ $dailyReport->client_name or '' }}</td>
                <td>{{ $dailyReport->location or ''}}</td>
                <td>{{ $dailyReport->cvs_to_tl or ''}}</td>
                <td>{{ $dailyReport->cvs_to_client or ''}}</td>
                <td>{{ $dailyReport->user_name or '' }}</td>
                <td>{{ $dailyReport->candidate_status or '' }}</td>
            </tr>
        @endforeach

    </table>
@endsection