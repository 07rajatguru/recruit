@extends('adminlte::page')

@section('title', 'Users')

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

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2 col-sm-4">
                <a href="{{ route('users.list','active') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5cb85c;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Current Users">Current Users ({{ $active }})</div></a>
            </div>

            <div class="col-md-2 col-sm-4">
                <a href="{{ route('users.list','inactive') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#d9534f;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;" title="Left Users">Left Users ({{ $inactive }}) </div></a>
            </div>
        </div>
    </div><br/>

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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="users_table">
        <thead>
            <tr>
                <th width="2%">No</th>
                <th width="7%">Action</th>
                <th width="5%">Job Open to all</th>
                <th width="8%">Name</th>
                <th width="25%">Email</th>
                <th>Department</th>
                <th>Role</th>
                <th>Working <br/>Hours</th>
                <th>Half Day <br/>Working Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; ?>
            @foreach ($users as $key => $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a class="fa fa-eye" title="Show" href="{{ route('users.show',\Crypt::encrypt($user->id)) }}"></a>
                        <a class="fa fa-edit" title="Edit" href="{{ route('users.edit',\Crypt::encrypt($user->id)) }}"></a>

                        @permission(('user-delete'))
                            @include('adminlte::partials.deleteModalUser', ['data' => $user, 'name' => 'users','display_name'=>'User'])
                        @endpermission
                        
                        @permission(('edit-user-profile'))

                            <a class="fa fa-user" title="View Profile" href="{{ route('users.myprofile',\Crypt::encrypt($user->id)) }}" target="_blank"></a>

                        @endpermission
                    </td>

                    @if($user->job_open_to_all == 1)
                        <td>
                            <center><input type="checkbox" checked=true id="{{ $user->id }}" class="check_job"></center>
                        </td>
                    @else
                        <td>
                            <center><input type="checkbox" id="{{ $user->id }}" class="check_job"><center>
                        </td>
                    @endif

                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->department }}</td>
                    <td>
                        @if(!empty($user->roles))
                            @foreach($user->roles as $v)
                                <label class="label label-success">{{ $v->display_name }}</label>
                            @endforeach
                        @endif
                    </td>

                    <td>{{ $user->working_hours }}</td>
                    <td>{{ $user->half_day_working_hours }}</td>
                    <td>{{ $user->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">

@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#users_table').DataTable({
                responsive: true,
                stateSave : true,
                "columnDefs": [ 
                    { "targets": 1, "searchable": false, "orderable": false },
                    { "targets": 2, "searchable": false, "orderable": false },
                ],
            });

            new jQuery.fn.dataTable.FixedHeader(table);

            $(document).on('click', '.check_job', function (e) {

                var url = 'users/jobopentoall';
                var token = $('input[name="csrf_token"]').val();
                var check = $(this).is(":checked");
                var id = $(this).attr('id');
                if (check == true) {
                    var checked = 1;
                }
                else {
                    var checked = 0;
                }

                $.ajax({
                    url : url,
                    type : "POST",
                    data : {checked:checked,id:id,'_token':token},
                    dataType:'json',
                    success: function(res){

                    }
                });
            });

        });
    </script>
@endsection