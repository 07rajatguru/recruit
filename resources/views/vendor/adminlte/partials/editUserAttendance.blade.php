<a href="#" data-toggle="modal" data-target="#attendanceModal" class="btn btn-success">Edit Attendance</a>

<div id="attendanceModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 id="modalTitle" class="modal-title">Edit Users Attendance</h2>
            </div>
            {!! Form::open(['method' => 'POST','autocomplete' => 'off','route' => 'userattendance.store']) !!}
            <div id="modalBody" class="modal-body"> 

                @if(isset($name) && $name != '')
                    <input type="hidden" name="name" id="name" value="{{ $name }}">
                @endif

                @if(isset($users) && sizeof($users) > 0)
                    @permission(('add-remarks-of-all-users-in-admin-panel'))
                        <strong>Select User :</strong><br/>
                        {!! Form::select('attendance_user_id', $users,null, array('id'=>'attendance_user_id','class' => 'form-control')) !!}<br/><br/>
                    @endpermission
                @endif
                
                <strong>Select Date: </strong>
                {!! Form::text('attendance_date',null,array('id' => 'attendance_date', 'placeholder' => 'Select Date', 'class' => 'form-control'))!!}
                <br/>
                <strong>Remark: </strong>
                {!! Form::textarea('attendance_remarks',null,array('id' => 'attendance_remarks', 'placeholder' => 'Remarks', 'class' => 'form-control', 'rows' => '2'))!!}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>