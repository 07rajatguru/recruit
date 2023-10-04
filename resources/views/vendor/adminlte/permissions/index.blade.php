@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Permissions Management</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('permission.create') }}"> Create New Permission</a>
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
            <th>No</th>
            <th>Name</th>
            <th>Description</th>
            <th width="280px">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach ($permissions as $key => $permission)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $permission->display_name }}</td>
                <td>{{ $permission->description }}</td>
                <td>
                    <a class="fa fa-edit" title="Edit" href="{{ route('permission.edit',\Crypt::encrypt($permission->id)) }}"></a>

                    @include('adminlte::partials.deleteModal', ['data' => $permission, 'name' => 'permission','display_name'=>'Permission'])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var table = jQuery('#roles_table').DataTable({
                responsive: true,
                stateSave : true,
            });

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection