<a data-toggle="modal" href="#modal-remarks-{!! $data['id'] !!}" class="fa fa-plus" title="Add Remarks"></a>

<div id="modal-remarks-{!! $data['id']!!}" class="modal text-left fade" style="width:100%;">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'POST', 'route' => ["$name.addremarks", $data['id']]])!!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">{{ $data['added_by'] }} - {{ $data['added_date'] }}</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <strong>Select Task : </strong><br/><br/>
                    @if(isset($data['task_list']) && sizeof($data['task_list']) > 0)
                        <select name="task_id" id="task_id_{{ $data['id'] }}" class="form-control task" onchange="setData('{{ $data['id'] }}');">

                            <option value="">Select Task</option>
                            @foreach($data['task_list'] as $key=>$value)
                                <option value="{{ $value['work_planning_list_id'] }}">{!! $value['task'] !!}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="form-group">
                    <strong>Projected Time : </strong>
                    {!! Form::text('projected_time', null, array('id'=>'projected_time_'.$data['id'],'placeholder' => 'Projected Time','class' => 'form-control','disabled')) !!}
                </div>

                <div class="form-group">
                    <strong>Actual Time : </strong>
                    {!! Form::text('actual_time', null, array('id'=>'actual_time_'.$data['id'],'placeholder' => 'Actual Time','class' => 'form-control','disabled')) !!}
                </div>

                <div class="form-group">
                    <strong>Remarks : </strong>
                    {!! Form::textarea('remarks', null, array('id'=>'remarks_'.$data['id'],'placeholder' => 'Remarks','class' => 'form-control remarks','rows' => '4','disabled')) !!}
                </div>

                <div class="form-group">
                    <strong>Reporting Manager / HR Remarks : </strong>
                    {!! Form::textarea('rm_hr_remarks', null, array('id'=>'rm_hr_remarks_'.$data['id'],'placeholder' => 'Reporting Manager / HR Remarks','class' => 'form-control rm_hr_remarks')) !!}
                </div>
            </div>

            <input type="hidden" name="wp_id" id="wp_id" value="{{ $data['id'] }}">
            <input type="hidden" name="page" id="page" value="{{ $page }}">
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>