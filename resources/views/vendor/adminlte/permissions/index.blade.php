@extends('adminlte::page')

@section('title', 'HRM')

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

                {{--@permission(('permision-create'))--}}

                <a class="btn btn-success" href="{{ route('permission.create') }}"> Create New Permission</a>

                {{--@endpermission--}}

            </div>

        </div>

    </div>

    @if ($message = Session::get('success'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

    @endif

    @if ($message = Session::get('error'))

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
        @foreach ($permissions as $key => $permission)

            <tr>

                <td>{{ ++$i }}</td>

                <td>{{ $permission->display_name }}</td>

                <td>{{ $permission->description }}</td>

                <td>

{{--                    <a class="btn btn-info" href="{{ route('permission.show',$permission->id) }}">Show</a>--}}

                    {{--@permission(('permission-edit'))--}}

                    <a class="btn btn-primary" href="{{ route('permission.edit',$permission->id) }}">Edit</a>

                    {{--@endpermission--}}

                    {{--@permission(('permision-delete'))--}}

{{--                    {!! Form::open(['method' => 'DELETE','route' => ['permission.destroy', $permission->id],'style'=>'display:inline']) !!}--}}

{{--                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}--}}

                    {{--{!! Form::close() !!}--}}

                    @include('adminlte::partials.deleteModal', ['data' => $permission, 'name' => 'permission','display_name'=>'Permission'])

                    {{--@endpermission--}}

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