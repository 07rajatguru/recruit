@extends('adminlte::page')

@section('title', 'Modules')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Module List</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('module.create') }}"> Add New Module</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="modules_table">
        <thead>
        	<tr>
	            <th>No</th>
	            <th>Name</th>
	            <th>Description</th>
	            <th width="280px">Action</th>
	        </tr>
    	</thead>
        <tbody>
        <?php $i=0; ?>
        @foreach ($modules as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $value['name'] }}</td>
                <td>{{ $value['description'] }}</td>
                <td>
                    <a class="fa fa-edit" title="edit" href="{{ route('module.edit',\Crypt::encrypt($value['id'])) }}"></a>

                    @permission(('module-delete'))
                        @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'module','display_name'=>'Module'])
                    @endpermission
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#modules_table').DataTable({
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