<a data-toggle="modal" href="#modal-remarks-{!! $data['work_planning_list_id'] !!}" title="Add Remarks">Add Remarks</a>

<div id="modal-remarks-{!! $data['work_planning_list_id']!!}" class="modal text-left fade" style="width:100%;">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'POST', 'route' => ["$name.addremarks", $data['work_planning_list_id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">{{ $work_planning['added_by'] }} - {{ $work_planning['added_date'] }}</h3>
            </div>
            <div class="modal-body">

                @if(isset($data['rm_hr_remarks']) && $data['rm_hr_remarks'] != '')
                    <div class="form-group">
                        <textarea id="rm_hr_remarks_{{ $data['work_planning_list_id'] }}" name="rm_hr_remarks" class="form-control" rows="5" placeholder = "RM / HR Remarks">{{ $data['rm_hr_remarks'] }}</textarea>
                    </div>
                @else
                    <div class="form-group">
                        <textarea id="rm_hr_remarks_{{ $data['work_planning_list_id'] }}" name="rm_hr_remarks" class="form-control" rows="5" placeholder = "RM / HR Remarks"></textarea>
                    </div>
                @endif
            </div>

            <input type="hidden" name="wp_id" id="wp_id" value="{{ $data['work_planning_id'] }}">
            <input type="hidden" name="task_id" id="task_id" value="{{ $data['work_planning_list_id'] }}">
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>