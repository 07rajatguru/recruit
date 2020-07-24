@extends('adminlte::page')

@section('title', 'Customer Support')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Support List</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('customer.create') }}"> Add New Support</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-error">
        <p>{{ $message }}</p>
    </div>
@endif

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="customer_table">
    <thead>
        <tr>
	       <th>No</th>
	       <th>Name</th>
           <th>Module</th>
           <th>Subject</th>
	       <th width="280px">Action</th>
	    </tr>
    </thead>
    <tbody>
        <?php $i=0; ?>
        @foreach ($customer_support_res as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $value['user_nm'] }}</td>
                <td>{{ $value['module'] }}</td>
                <td>{{ $value['subject'] }}</td>

                <td>
                    <a class="fa fa-circle" href="{{ route('customer.show',$value['id']) }}">
                    </a>
                    <a class="fa fa-edit" href="{{ route('customer.edit',$value['id']) }}"></a>

                    @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'customer','display_name'=>'Support'])
               </td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#customer_table').DataTable({
                responsive: true,
                stateSave : true,
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection