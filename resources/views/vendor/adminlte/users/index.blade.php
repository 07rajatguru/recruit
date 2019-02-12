@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Users Management</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="users_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Type</th>
                <th>Status</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($data as $key => $user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if(!empty($user->roles))
                        @foreach($user->roles as $v)
                            <label class="label label-success">{{ $v->display_name }}</label>
                        @endforeach
                    @endif
                </td>
                <td>{{ $user->type }}</td>
                <td>{{ $user->status }}</td>
                <td>
                    <a class="fa fa-eye" title="Show" href="{{ route('users.show',$user->id) }}"></a>
                    <a class="fa fa-edit" title="Edit" href="{{ route('users.edit',$user->id) }}"></a>
                    {{--{!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}--}}
                    @include('adminlte::partials.deleteModalUser', ['data' => $user, 'name' => 'users','display_name'=>'User'])
                    @if($isSuperAdmin || $isAccountant)
                        <a class="fa fa-user" title="Edit Profile" href="{{ route('users.editprofile',$user->id) }}"></a>
                    @endif
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
  {{--!! $data->render() !!--}}
@endsection
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#users_table').DataTable( {
                responsive: true,
                stateSave : true,
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection