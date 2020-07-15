@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Permissions Management({{ $count or 0}})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('userpermission.create') }}"> Create New Permission</a>
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="permissions_table">
        <thead>
        <tr>
            <th>No</th>
            <th>Module</th>
            <th>Name</th>
            <th>Description</th>
            <th width="280px">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>

        @if(isset($permissions) && sizeof($permissions) > 0)
            @foreach($permissions as $key => $permission)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $permission['module_name'] }}</td>
                    <td>{{ $permission['display_name'] }}</td>
                    <td>{{ $permission['description'] }}</td>
                    <td>
                        <a class="fa fa-edit" title="Edit" href="{{ route('userpermission.edit',$permission['id']) }}"></a>
                       
                        @include('adminlte::partials.deleteModal', ['data' => $permission, 'name' => 'userpermission','display_name'=>'Permission'])
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            
            var table = jQuery('#permissions_table').DataTable({
                responsive: true,
                stateSave : true,
            });
            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection