@extends('adminlte::page')

@section('title', 'Expense')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Expense List ({{ $count }})</h2>
            </div>

            <div class="pull-right">
             @permission(('expense-create')) 
                <a class="btn btn-primary" href="{{ route('expense.importExport') }}"> Import Expense</a>
                <a class="btn btn-success" href="{{ route('expense.create') }}"> Create New Expense</a>
             @endpermission
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
                <th>Action</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Paid To</th>
                <th>Expense<br/> Head</th>
                <th>Remarks</th>
                <th>Payment<br/> Mode</th>
                <th>Payment<br/> Type</th>
                <th>Reference <br/>Number</th>
            </tr>
        </thead>

        <tbody>            
        <?php $i = 0 ;?>
        @foreach($expense as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>  
                    <a title="Show" class="fa fa-circle"  href="{{ route('expense.show',$value['id']) }}"></a>

                    @permission(('expense-edit'))          
                    <a class="fa fa-edit" href="{{ route('expense.edit',$value['id']) }}" title="Edit"></a>
                    @endpermission

                    @permission(('expense-delete'))
                    @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'expense','display_name'=>'expense'])
                    @endpermission                   
                </td>
                <td>{{ $value['date'] or '' }}</td>
                <td>{{ $value['paid_amount'] or '' }}</td>
                <td>{{ $value['paid_to'] or '' }}</td>
                <td>{{ $value['expense_head'] or '' }}</td>
                <td>{{ $value['remarks'] or '' }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['payment_mode'] or '' }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['payment_type'] or '' }}</td>
                <td>{{ $value['number'] or '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
@stop
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#expense_table').DataTable( {
                responsive: true,
                stateSave : true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection