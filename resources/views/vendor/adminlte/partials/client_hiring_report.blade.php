<a data-toggle="modal" href="#modal-hiring-report-{!! $data['id'] !!}" class="fa fa-file-o" title="Generate Hiring Report" style="margin:2px;"></a>
<div id="modal-hiring-report-{!! $data['id'] !!}" class="modal fade">
	<div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title"><b>{{ $data['full_name'] }}</b></h5>
            </div>

            <div class="modal-body">
                <div class="col-xs-6">
                    <div class="form-group">
                        <strong>From Date : <span class = "required_fields">*</span></strong>
                        <div class="input-group date">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            {!! Form::text('from_date',null, array('id'=>'from_date_'.$data['id'],'placeholder' => 'From Date','class' => 'form-control from_date_class', 'tabindex' => '1','autocomplete' => 'off','style' => 'width:225px;')) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <strong>To Date : <span class = "required_fields">*</span></strong>
                    <div class="input-group date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        {!! Form::text('to_date',null, array('id'=>'to_date_'.$data['id'],'placeholder' => 'To Date','class' => 'form-control to_date_class', 'tabindex' => '2','autocomplete' => 'off','style' => 'width:225px;')) !!}
                    </div>
                </div>
            </div>

            <input type="hidden" name="page_nm" id="page_nm" value="{{ $page }}">
            <input type="hidden" name="source" id="source" value="{{ $source }}">

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="generateLastestHiringReport({{ $data['id'] }})" style="margin-bottom: 5px;">Generate Last 15 Days Hiring Report</button>
                <button type="button" class="btn btn-primary" onclick="generateHiringReport({{ $data['id'] }})" style="margin-bottom: 5px;">Generate Hiring Report</button>
                <button type="button" class="btn btn-primary" onclick="downloadHiringReport({{ $data['id'] }})" style="margin-bottom: 5px;">Download Excel</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;" >Cancel</button>
            </div>
        </div>
    </div>
</div>