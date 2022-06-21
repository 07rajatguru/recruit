<a href="#" data-toggle="modal" data-target="#calendarModal" class="btn btn-success">Add Remarks</a>

<div id="calendarModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 id="modalTitle" class="modal-title">Add Remark</h2>
            </div>
            {!! Form::open(['method' => 'POST','autocomplete' => 'off','route' => 'userremarks.store','id' => 'remarks_form']) !!}
            <div id="modalBody" class="modal-body"> 

                @if(isset($name) && $name != '')
                    <input type="hidden" name="name" id="name" value="{{ $name }}">
                @endif

                <input type="hidden" name="month" id="month" value="{{ $month }}">
                <input type="hidden" name="year" id="year" value="{{ $year }}">

                @if(isset($users) && sizeof($users) > 0)
                    @permission(('add-remarks-of-all-users-in-admin-panel'))
                        <strong>Select User :</strong><br/>
                        {!! Form::select('user_id', $users,null, array('id'=>'user_id','class' => 'form-control')) !!}<br/><br/>
                    @endpermission
                @endif
                
                <strong>Select Date: </strong>
                {!! Form::text('date',null,array('id' => 'date', 'placeholder' => 'Select Date', 'class' => 'form-control'))!!}
                <br/>
                <strong>Remark: </strong>
                {!! Form::textarea('remarks',null,array('id' => 'remarks', 'placeholder' => 'Remarks', 'class' => 'form-control', 'rows' => '2'))!!}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>