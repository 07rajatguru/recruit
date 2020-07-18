@extends('adminlte::page')

@section('title', 'Module Visible Users')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Module Visible User List</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('modulevisible.create') }}"> Add New Module Visibility</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="modules_visible_table">
        <thead>
        	<tr>
	            <th>No</th>
	            <th>Action</th>
                <th>User Name</th>
                <th>Modules Name</th>
	        </tr>
    	</thead>
        <tbody>
        <?php $i=0; ?>
        @foreach ($module_user as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="fa fa-edit" title="edit" href="{{ route('modulevisible.edit',$value['main_id']) }}"></a>

                    @permission(('module-visibility-delete'))
                        @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'modulevisible','display_name'=>'Module Visible User'])
                    @endpermission
                </td>
                <td>{{ $value['uname'] }}</td>
                <td>{{ $value['mname'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#modules_visible_table').DataTable( {
                responsive: true,
                stateSave : true,
            } );

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection