@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Role Management</h2>
            </div>

            <div class="pull-right">
                @permission(('role-add'))
                    <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a>
                @endpermission
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="roles_table">
        <thead>
        <tr>
            <th width="40px">No</th>
            <th width="100px">Action</th>
            <th>Name</th>
            <th>Description</th>
            <th>Department</th>
            
        </tr>
    </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach ($roles as $key => $role)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="fa fa-circle" title="Show" href="{{ route('roles.show',\Crypt::encrypt($role->id)) }}"></a>

                    @permission(('role-edit'))
                        <a class="fa fa-edit" title="Edit" href="{{ route('roles.edit',\Crypt::encrypt($role->id)) }}"></a>
                    @endpermission

                    @permission(('role-delete'))
                        @include('adminlte::partials.deleteModalUser', ['data' => $role, 'name' => 'roles','display_name'=>'Role'])
                    @endpermission
                </td>
                <td>{{ $role->display_name }}</td>
                <td>{{ $role->description }}</td>
                <td>{{ $role->department }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#roles_table').DataTable({
                responsive: true,
                stateSave : true,
                "columnDefs": [ {orderable: false, targets: [1]}],
            });

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection