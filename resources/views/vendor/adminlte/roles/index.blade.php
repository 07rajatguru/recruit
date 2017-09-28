@extends('adminlte::page')

@section('title', 'HRM')

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

                @permission(('role-create'))

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
        @foreach ($roles as $key => $role)

            <tr>

                <td>{{ ++$i }}</td>

                <td>{{ $role->display_name }}</td>

                <td>{{ $role->description }}</td>

                <td>

                    <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>

                    @permission(('role-edit'))

                    <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>

                    @endpermission

                    @permission(('role-delete'))

                    {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}

                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}

                    {!! Form::close() !!}

                    @endpermission

                </td>

            </tr>

        @endforeach
        </tbody>
    </table>

@endsection
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#roles_table').DataTable( {
                responsive: true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection