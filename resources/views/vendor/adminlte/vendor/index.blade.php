@extends('adminlte::page')

@section('title', 'Vendor')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
             <div class="pull-left">
                <h2>Vendor List ({{ $count }})</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('vendor.create') }}"> Create New Vendor</a>
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="vendor_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Vendor Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Contact Point</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
       <?php $i=0; ?>
        @foreach ($vendor_array as $key => $vendor)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $vendor['name'] }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $vendor['mobile'] }}</td>
                <td>{{ $vendor['mail'] }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $vendor['contact_point'] }}</td>

                <td>
                        <a title="Show" class="fa fa-circle"  href="{{ route('vendor.show',$vendor['id']) }}"></a>

                        <a title="Edit" class="fa fa-edit" href="{{ route('vendor.edit',$vendor['id']) }}"></a>

                        @include('adminlte::partials.deleteModal', ['data' => $vendor, 'name' => 'vendor','display_name'=>'vendor'])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#vendor_table').DataTable( {
                responsive: true,
                "pageLength": 100
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection
