@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Expense</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('expense.create') }}"> Create New Expense</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table id="expense_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Paid To</th>
                <th>Expense Head</th>
                <th>Remarks</th>
                <th>Payment Mode</th>
                <th>Payment Type</th>
                <th>Number</th>
                <th width="280px">Action</th>
            </tr>
        </thead>

        <tbody>            
        <?php $i = 0 ;?>
       
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ '' }}</td>
                <td>{{ '' }}</td>
                <td>{{ '' }}</td>
                <td>{{ '' }}</td>
                <td>{{ '' }}</td>
                <td>{{ '' }}</td>
                <td>{{ '' }}</td>
                <td>{{ '' }}</td>
                <td>            
                    
                </td>
            </tr>
       
        </tbody>
    </table>
    
@stop
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#expense_table').DataTable( {
                responsive: true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection