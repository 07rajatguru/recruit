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
        @foreach($expense as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $value['date'] or '' }}</td>
                <td>{{ $value['amount'] or '' }}</td>
                <td>{{ $value['paid_to'] or '' }}</td>
                <td>{{ $value['expense_head'] or '' }}</td>
                <td>{{ $value['remarks'] or '' }}</td>
                <td>{{ $value['payment_mode'] or '' }}</td>
                <td>{{ $value['payment_type'] or '' }}</td>
                <td>{{ $value['number'] or '' }}</td>
                <td>            
                    <a class="fa fa-edit" href="{{ route('expense.edit',$value['id']) }}" title="Edit"></a>

                    <?php if($isSuperAdmin) {?>
                        @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'expense','display_name'=>'Expense'])
                    <?php   }?>
                </td>
            </tr>
        @endforeach
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