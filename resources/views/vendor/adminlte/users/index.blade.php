@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Users Management({{ $count or 0}})</h2>
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

    @if ($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- For Users others information popup-->

    <input type="hidden" name="users_string" id="users_string" value="{{ $users_string }}">

    <div id="usersModal" class="modal text-left fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Information</h4>
                </div>
                <div class="modal-body">
                    <p>
                        Please fill up remaining details of following Users : <br/><br/>
                        @if(isset($users_string) && $users_string != '')
                            <?php
                                $users_name_array = explode(",", $users_string);
                            ?>

                            @if(isset($users_name_array) && sizeof($users_name_array) > 0)
                                <?php $i=1; ?>
                                @foreach($users_name_array as $key => $value)
                                    <?php
                                        echo $i;
                                        echo ".    ";
                                        echo $value;
                                    ?>
                                <br/><?php $i++ ?>
                                @endforeach
                            @endif
                        @endif
                    </p><br/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- End Users others information popup-->

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="users_table">
        <thead>
            <tr>
                <th width="2%">No</th>
                <th width="8%">Action</th>
                <th width="5%">Job Open to all</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; ?>
            @foreach ($users as $key => $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a class="fa fa-eye" title="Show" href="{{ route('users.show',$user->id) }}"></a>
                        <a class="fa fa-edit" title="Edit" href="{{ route('users.edit',$user->id) }}"></a>

                        @permission(('user-delete'))
                            @include('adminlte::partials.deleteModalUser', ['data' => $user, 'name' => 'users','display_name'=>'User'])
                        @endpermission
                        
                        @permission(('edit-user-profile'))

                            <a class="fa fa-user" title="View Profile" href="{{ route('users.myprofile',$user->id) }}" target="_blank"></a>

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
                    <td>{{ $user->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
    <input type="hidden" name="superadmin" id="superadmin" value="{{ $superadmin }}">
    <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">
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

            var users_string = $("#users_string").val();

            if(users_string != '') {

                var superadmin = $("#superadmin").val();
                var user_id = $("#user_id").val();

                if(superadmin == user_id) {

                    var event = new Date();
                    var options = { weekday: 'long' };
                    var day = event.toLocaleDateString('en-US', options);

                    var hours = event.getHours();
                    var minutes = event.getMinutes();

                    if((day == 'Saturday' && hours == '10') || (day == 'Saturday' && hours == '17' && minutes == '0')) {
                        jQuery("#usersModal").modal('show');
                    }
                }
            }

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