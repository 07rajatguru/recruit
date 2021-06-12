@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

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

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if($action == 'edit')
                <h2>Edit Job Openings</h2>
            @elseif( $action == 'clone')
                <h2>Clone Job</h2>
            @else
                <h2>Create Job Openings</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('jobopen.index') }}">Back</a>
        </div>
    </div>
</div>

@if( $action == 'edit')
    {!! Form::model($job_open,['method' => 'PATCH','files' => true, 'id' => 'jobsForm','autocomplete' => 'off','route' => ['jobopen.update', $job_open->id]] ) !!}
@elseif( $action == 'clone')
    {!! Form::model($job_open,['method' => 'POST','files' => true, 'id' => 'jobsForm','autocomplete' => 'off','route' => ['jobopen.clonestore']] ) !!}
@else
    {!! Form::open(array('route' => 'jobopen.store','files' => true,'method'=>'POST', 'id' => 'jobsForm','autocomplete' => 'off')) !!}
@endif

@if(isset($year) && $year != '')
    <input type="hidden" name="year" id="year" value="{{ $year }}">
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Basic Information</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="col-md-4" style="margin-left: -15px;">
                            <div class="form-group {{ $errors->has('level_id') ? 'has-error' : '' }}">
                                <strong>Posting Title : <span class = "required_fields">*</span> </strong>
                                {!! Form::select('level_id', $client_hierarchy_name, null, array('id'=>'level_id','class' => 'form-control', 'tabindex' => '1','onchange' => 'removeError();')) !!}
                                @if ($errors->has('level_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('level_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8" style="width: 72%;margin: 20px 0 0 -20px;">
                            <div class="form-group {{ $errors->has('posting_title') ? 'has-error' : '' }}">
                                {!! Form::text('posting_title', null, array('id'=>'posting_title','placeholder' => 'Posting Title','class' => 'form-control', 'tabindex' => '1','style' => 'width:402px','onchange' => 'validPostingTitleText();')) !!}
                                @if ($errors->has('posting_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('posting_title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- <div class="form-group {{ $errors->has('posting_title') ? 'has-error' : '' }}">
                            <strong>Posting Title: <span class = "required_fields">*</span> </strong>
                            {!! Form::text('posting_title', null, array('id'=>'posting_title','placeholder' => 'Posting Title','class' => 'form-control', 'tabindex' => '1')) !!}
                            @if ($errors->has('posting_title'))
                                <span class="help-block">
                                <strong>{{ $errors->first('posting_title') }}</strong>
                                </span>
                            @endif
                        </div>
 -->
                        <div class="form-group {{ $errors->has('hiring_manager_id') ? 'has-error' : '' }}">
                            <strong>Select Hiring Manager :</strong>
                                {!! Form::select('hiring_manager_id', $users,$user_id, array('id'=>'hiring_manager_id','class' => 'form-control', 'tabindex' => '3')) !!}
                              @if ($errors->has('hiring_manager_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('hiring_manager_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('date_opened') ? 'has-error' : '' }}">
                            <strong>Date Opened : <span class = "required_fields">*</span> </strong>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar open_class"></i>
                                </div>  
                                {!! Form::text('date_opened', isset($date_opened) ? $date_opened : null, array('id'=>'date_opened','placeholder' => 'Date Opened','class' => 'form-control', 'tabindex' => '5')) !!}
                            </div>
                            @if ($errors->has('date_opened'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('date_opened') }}</strong>
                                </span>
                            @endif
                        </div>

                        {{--<div class="form-group {{ $errors->has('job_opening_status') ? 'has-error' : '' }}">
                            <strong>Select Job Opening Status :</strong>
                            {!! Form::select('job_opening_status', $job_open_status,null, array('id'=>'job_opening_status','class' => 'form-control')) !!}
                            @if ($errors->has('job_opening_status'))
                                <span class="help-block">
                                <strong>{{ $errors->first('job_opening_status') }}</strong>
                                </span>
                            @endif
                        </div>--}}

                        <div class="form-group {{ $errors->has('industry_id') ? 'has-error' : '' }}">
                            <strong>Industry Type :  <span class = "required_fields">*</span>  </strong>
                            {!! Form::select('industry_id', $industry,null, array('id'=>'industry_id','class' => 'form-control', 'tabindex' => '7')) !!}
                            @if ($errors->has('industry_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('industry_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('job_priority') ? 'has-error' : '' }}">
                            <strong>Select Job Priority : </strong>
                            {!! Form::select('job_priority', $job_priorities,(isset($job_open->priority) ? $job_open->priority : null), array('id'=>'job_priority','class' => 'form-control', 'tabindex' => '8')) !!}
                            @if ($errors->has('job_priority'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('job_priority') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('job_type') ? 'has-error' : '' }}">
                            <strong>Select Job Type : </strong>
                            {!! Form::select('job_type', $job_type,null, array('id'=>'job_type','class' => 'form-control', 'tabindex' => '9')) !!}
                            @if ($errors->has('job_type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('job_type') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">

                        <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                            <strong>Select Client : <span class = "required_fields">*</span></strong>
                            {!! Form::select('client_id', $client,null, array('id'=>'client_id','class' => 'form-control', 'tabindex' => '2', 'onchange' => 'getClientId()')) !!}
                            @if ($errors->has('client_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('client_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('no_of_positions') ? 'has-error' : '' }}">
                            <strong>Number of Positions :</strong>
                            @if($action == 'edit')
                                {!! Form::text('no_of_positions',null, array('id'=>'no_of_positions','placeholder' => 'Number of Positions','class' => 'form-control' , 'tabindex' => '4')) !!}
                            @else
                                {!! Form::text('no_of_positions', $no_of_positions, array('id'=>'no_of_positions','placeholder' => 'Number of Positions','class' => 'form-control', 'tabindex' => '4')) !!}
                            @endif
                            @if ($errors->has('no_of_positions'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('no_of_positions') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('target_date') ? 'has-error' : '' }}">
                            <strong>Target Date :</strong>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar target_class"></i>
                                </div>
                                {!! Form::text('target_date', isset($target_date) ? $target_date : null, array('id'=>'target_date','placeholder' => 'Target Date','class' => 'form-control', 'tabindex' => '6')) !!}
                            </div>
                            @if ($errors->has('target_date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('target_date') }}</strong>
                                </span>
                            @endif
                        </div>

                        {{--<div class="form-group {{ $errors->has('job_type') ? 'has-error' : '' }}">
                            <strong>View To All :</strong>
                            {{ Form::radio('job_show', 1, false, ['class' => 'field']) }}
                            <strong>Within Team :</strong>
                            {{ Form::radio('job_show', 0, true, ['class' => 'field']) }}
                        </div>--}}

                        <div class="form-group {{ $errors->has('qualifications') ? 'has-error' : '' }}">
                            <strong>Education Qualifications :</strong>
                            {!! Form::textarea('qualifications', null, array('id'=>'qualifications','placeholder' => 'Education Qualifications','class' => 'form-control','tabindex' => '10','rows' => '8')) !!}
                            @if ($errors->has('qualifications'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('qualifications') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-12 col-sm-12 col-md-12">

                    <div class="form-group {{ $errors->has('qualifications') ? 'has-error' : '' }}">
                        <strong>Desired Candidates :</strong>
                        {!! Form::textarea('desired_candidate', null, array('id'=>'desired_candidate','rows'=>'3','placeholder' => 'Desired Candidate','class' => 'form-control', 'tabindex' => '11')) !!}
                        @if ($errors->has('desired_candidate'))
                            <span class="help-block">
                                <strong>{{ $errors->first('desired_candidate') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('user_ids') ? 'has-error' : '' }}">
                        <strong>Select Users who can access the job : <span class = "required_fields">*</span></strong>
                        <input type="checkbox" id="users_all"/> <strong>Select All</strong><br/>
                        @foreach($select_all_users as $k=>$v)&nbsp;&nbsp; 
                            {!! Form::checkbox('user_ids[]', $k, in_array($k,$selected_users), array('id'=>'user_ids','size'=>'10','class' => 'users_ids')) !!}
                            {!! Form::label ($v) !!}
                        @endforeach

                        @if ($errors->has('user_ids'))
                            <span class="help-block">
                                <strong>{{ $errors->first('user_ids') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="">
                        <div class="form-group {{ $errors->has('job_description') ? 'has-error' : '' }}">
                            <strong>Job Description :</strong>
                            {!! Form::textarea('job_description', null, array('id'=>'job_description','placeholder' => 'Job description','class' => 'form-control', 'tabindex' => '12')) !!}
                            @if ($errors->has('no_of_positions'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('job_description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($loggedin_user_id == $super_admin_user_id)
                        <div class="">
                            <div class="form-group">
                                <strong>If you don't want to open the job after 48 hours please check the checkbox :</strong>
                                {!! Form::checkbox('job_open_checkbox','1', $job_open_checkbox, array('id' => 'job_open_checkbox','tabindex' => '13')) !!}
                            </div>
                        </div>
                    @endif

                    <div class="">
                        <div class="form-group">
                            <strong>Do you wish to post this job on our Adler’s Current Openings Page? :</strong>
                            {!! Form::checkbox('adler_career_checkbox','1', $adler_career_checkbox, array('id' => 'adler_career_checkbox','tabindex' => '14')) !!}
                        </div>
                    </div>

                    <div class="">
                        <div class="form-group">
                            <strong>If you wish to disclose the salary on Adler’s Current Openings Page, please uncheck the checkbox. :</strong>
                            {!! Form::checkbox('adler_job_disclosed_checkbox','1', $adler_job_disclosed_checkbox, array('id' => 'adler_job_disclosed_checkbox','tabindex' => '15')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Additional Information</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                        <div class="">
                            <strong>Work Experience from :</strong>
                        </div>
                    </div>
                    <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('work_experience_from') ? 'has-error' : '' }}">
                            {!! Form::select('work_experience_from', $work_from, $work_exp_from, array('id'=>'work_experience_from','class' => 'form-control','tabindex' => '13')) !!}
                            @if ($errors->has('work_experience_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('work_experience_from') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>

                
            <div class="box-body col-xs-6 col-sm-6 col-md-6">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <strong>Work Experience to :</strong>
                    </div>
                </div>
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <div class="form-group {{ $errors->has('work_experience_to') ? 'has-error' : '' }}">
                            {!! Form::select('work_experience_to', $work_to, $work_exp_to, array('id'=>'work_experience_to','class' => 'form-control','tabindex' => '14')) !!}
                            @if ($errors->has('work_experience_to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('work_experience_to') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

                {{--<div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('salary_from') ? 'has-error' : '' }}">
                            <strong>Salary from :</strong>
                            {!! Form::text('salary_from', null, array('id'=>'salary_from','placeholder' => 'Salary From','class' => 'form-control', 'tabindex' => '15')) !!}
                            @if ($errors->has('salary_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('salary_from') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>--}}

                {{--<div class="box-body col-xs-3 col-sm-3 col-md-3">
                    <div class="">
                        <div class="form-group {{ $errors->has('salary_to') ? 'has-error' : '' }}">
                            <strong>Salary to :</strong>
                            {!! Form::text('salary_to', null, array('id'=>'salary_to','placeholder' => 'Salary To','class' => 'form-control','tabindex' => '16')) !!}
                            @if ($errors->has('salary_to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('salary_to') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>--}}
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <strong>Salary From : <span class = "required_fields"> *</span></strong>
                        <div class="form-group {{ $errors->has('annual_salary_from') ? 'has-error' : '' }}">
                            <div class="box-body col-xs-6 col-sm-6 col-md-6">
                                <div class="">
                                    {!! Form::select('lacs_from', $lacs, $lacs_from, array('id'=>'lacs_from','class' => 'form-control','tabindex' => '15')) !!}
                                </div>
                            </div>
                            <div class="box-body col-xs-6 col-sm-6 col-md-6">
                                <div class="">
                                    {!! Form::select('thousand_from', $thousand, $thousand_from, array('id'=>'thousand_from','class' => 
                                    'form-control','tabindex' => '16')) !!}
                                </div>
                            </div>
                            @if ($errors->has('annual_salary_from'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('annual_salary_from') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                        <strong>Salary To : <span class = "required_fields"> *</span></strong>
                        <div class="form-group {{ $errors->has('annual_salary_to') ? 'has-error' : '' }}">
                            <div class="box-body col-xs-6 col-sm-6 col-md-6">
                                <div class="">
                                    {!! Form::select('lacs_to', $lacs, $lacs_to, array('id'=>'lacs_to','class' => 'form-control', 'tabindex' => '17')) !!}
                                </div>
                            </div>
                            <div class="box-body col-xs-6 col-sm-6 col-md-6">
                                <div class="">
                                    {!! Form::select('thousand_to', $thousand, $thousand_to, array('id'=>'thousand_to','class' => 
                                    'form-control', 'tabindex' => '18')) !!}
                                </div>
                            </div>

                            @if ($errors->has('annual_salary_to'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('annual_salary_to') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header with-border col-md-6 ">
                <h3 class="box-title">Job Location</h3>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">
                    <div class="form-group">
                        <strong>Enter your location : </strong>
                        {!! Form::text('job_location', null, array('id'=>'job_location','placeholder' => 'Enter your location','class' => 'form-control' , 'onFocus'=>"geolocate()", 'tabindex' => '19')) !!}
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">
                        <div class="form-group">
                            <strong>City : <span class = "required_fields"> *</span></strong>
                            {!! Form::text('city', null, array('id'=>'city','placeholder' => 'City','class' => 'form-control', 'tabindex' => '20')) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">
                        <div class="form-group">
                            <strong>Country : </strong>
                            {!! Form::text('country', null, array('id'=>'country','placeholder' => 'Country','class' => 'form-control', 'tabindex' => '21')) !!}
                        </div>
                    </div>
                </div>

                <div class="box-body col-xs-4 col-sm-4 col-md-4">
                    <div class="">
                        <div class="form-group">
                            <strong>State : </strong>
                            {!! Form::text('state', null, array('id'=>'state','placeholder' => 'State','class' => 'form-control', 'tabindex' => '22')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="">
                        <div class="form-group">
                            <strong>Is this job going to be Remote Working or Working from Home? :</strong>
                            {!! Form::checkbox('remote_working','1', $remote_working, array('id' => 'remote_working','tabindex' => '23')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        @if( $action == 'add')
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-6 ">
                    <h3 class="box-title">Attachment Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Candidate Tracker : <!-- <span class = "required_fields"> *</span> --> [Allow only .doc, .docx, .pdf, .xlsx, .xls extension files.]</strong>
                        <input type="file" name="candidate_tracker"  id="candidate_tracker" class="form-control">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Job Description : [Allow only .doc, .docx, .pdf, .txt extension files.]</strong>
                        <input type="file" name="job_summary"  id="job_summary" class="form-control">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Others : </strong>
                        <input type="file" name="others_doc[]"  id="others_doc" class="form-control" multiple>
                    </div>
                </div>
            </div>
        @elseif($action == 'edit')
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header  col-md-6 ">
                        <h3 class="box-title">Attachments</h3>
                        &nbsp;&nbsp;
                        @include('adminlte::jobopen.upload', ['data' => $job_open, 'name' => 'jobopen'])
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <th></th>
                                <th>File Name</th>
                                <th>Uploaded by</th>
                                <th>Size</th>
                                <th>Category</th>
                            </tr>
                            @if(sizeof($job_open['doc'])>0)
                                @foreach($job_open['doc'] as $key=>$value)
                                    <tr>
                                        <td>
                                            <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                            &nbsp;
                                            @include('adminlte::partials.confirm', ['data' => $value,'id'=> $job_open['id'], 'name' => 'jobopenattachments' ,'display_name'=> 'Attachments','type' => 'edit'])
                                        </td>
                                        <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a></td>
                                        <td>{{ $value['uploaded_by'] }}</td>
                                        <td>{{ $value['size'] }}</td>
                                        <td>{{ $value['category'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        @elseif($action == 'clone')
            <input type="hidden" value="{{ $job_open->id }}" name="job_id" id="job_id"/>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header  col-md-6 ">
                        <h3 class="box-title">Attachments</h3>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <th></th>
                                <th>File Name</th>
                                <th>Uploaded by</th>
                                <th>Size</th>
                                <th>Category</th>
                            </tr>
                            @if(sizeof($job_open['doc'])>0)
                                @foreach($job_open['doc'] as $key=>$value)
                                    <tr>
                                        <td>
                                            <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                        </td>
                                        <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a></td>
                                        <td>{{ $value['uploaded_by'] }}</td>
                                        <td>{{ $value['size'] }}</td>
                                        <td>{{ $value['category'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        {!! Form::submit(isset($job_open) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
    </div>

<input type="hidden" id="action" name="action" value="{!! $action !!}">
<input type="hidden" id="super_admin_user_id" name="super_admin_user_id" value="{!! $super_admin_user_id !!}">
<input type="hidden" id="loggedin_user_id" name="loggedin_user_id" value="{{ $loggedin_user_id }}">

</div>

{!! Form::close() !!}

@section('customscripts')
    <script type="text/javascript">

        var action={!! json_encode($action) !!};
        $(document).ready(function() {

            $('#jobsForm').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $("#users_all").click(function () {
                $('.users_ids').prop('checked', this.checked);
            });

            $(".users_ids").click(function () {
                $("#users_all").prop('checked', ($('.users_ids:checked').length == $('.users_ids').length) ? true : false);
            });

            // Edit form if all user select then select all selected
            $("#users_all").prop('checked', ($('.users_ids:checked').length == $('.users_ids').length) ? true : false);

            $("#jobsForm").validate({
                rules: {
                   
                    "posting_title": {
                        required: true
                    },
                    "date_opened": {
                        required: true
                    },
                    "lacs_from":{
                        required:true
                    },
                    "lacs_to":{
                        required:true
                    },
                    "thousand_from":{
                        required:true
                    },
                    "thousand_to":{
                        required:true
                    },
                    "user_ids[]": {
                        required : true,
                    },
                    /*"candidate_tracker": {
                        required : true,
                    },*/
                    "city": {
                        required : true,
                    },
                    "level_id": {
                        required : true,
                    },
                },
                messages: {
                    
                    "posting_title": {
                        required: "Posting Title is Required Field."
                    },
                    "date_opened": {
                        required: "Date Open is Required Field."
                    },
                    "lacs_from": {
                        required: "Please Select Value"
                    },
                    "lacs_to": {
                        required: "Please Select Value"
                    },
                    "thousand_from": {
                        required: "Please Select Value"
                    },
                    "thousand_to": {
                        required: "Please Select Value"
                    },
                    "user_ids[]": {
                        required : "User is Required Field.",
                    },
                    /*"candidate_tracker": {
                        required : "Please Select Candidate Tracker",
                    },*/
                    "city": {
                        required : "City is Required Field.",
                    },
                    "level_id": {
                        required : "Please Select Position",
                    },
                }
            });

            getClientId();
        });

        $(function () {

            $("#target_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            $("#date_opened").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

            if(action == "add" || action == "clone") {

                $('#date_opened').datepicker().datepicker('setDate', 'today');
                var date2 = $('#date_opened').datepicker('getDate');
                date2.setMonth(date2.getMonth()+1);
                $('#target_date').datepicker('setDate', date2);
            }

            $('.target_class').click(function() {
                $("#target_date").focus();
            })

            $('.open_class').click(function() {
                $("#date_opened").focus();
            })

            $("#client_id").select2();
            $("#job_description").wysihtml5();
            $("#desired_candidate").wysihtml5();
            $("#industry_id").select2();

            /*$("#candidate_tracker").bind('change', function() {

                var ext = $('#candidate_tracker').val().split('.').pop().toLowerCase();

                if($.inArray(ext, ['doc','docx','pdf','xlsx','xls']) == -1) {

                    alert('Please Select Document File.');
                    this.value = null;
                }
            });*/

            $("#job_summary").bind('change', function() {

                var ext = $('#job_summary').val().split('.').pop().toLowerCase();

                if($.inArray(ext, ['doc','docx','pdf','txt']) == -1) {

                    alert('Please Select Document File.');
                    this.value = null;
                }
            });
        });

        function removeError() {

            var level_id = $("#level_id");

            if(level_id == '') {

            }
            else {
                document.getElementById("level_id-error").style.display = "none";
            }
        }
        
        function validPostingTitleText() {

            var txt = document.getElementById("posting_title").value ;
            var PostingTitleLength = txt.trim().length;

            if(PostingTitleLength < 1) {
                alert("Blank Entry Not Allowed.")
                document.getElementById("posting_title").value = '';
                document.getElementById("posting_title").focus();
            }
        }

        function salaryValidation() {

            var lacs_from = $("#lacs_from").val();
            var lacs_to = $("#lacs_to").val();

            if(lacs_from > lacs_to) {
                alert('Salary from should be less then or equal to Salary to.');
                return false;
            }
            return true;
        }

        function getClientId() {

            var client_id = $("#client_id").val();
            var app_url = "{!! env('APP_URL') !!}";
            var action = $("#action").val();

            if(client_id > 0) {

                $.ajax({

                    url:app_url+'/job/getClientInfos',
                    data:'client_id='+client_id,
                    dataType:'json',
                    success: function(response){
                       if(response.answer == 'True') {
                            document.getElementById("hiring_manager_id").value = response.am_id;
                       }
                       else {
                            document.getElementById("hiring_manager_id").value = response.user_id;
                       }

                       $("#hiring_manager_id").select2();

                       if(action == 'add') {

                           jQuery("input[name='user_ids[]']").each(function(i) {

                                if($(this).val() == response.am_id) {

                                    $("input[value='" + response.am_id + "']").prop('checked', true);
                                }
                                else {

                                    var a = ($(this).val());
                                    $("input[value='" + a + "']").prop('checked', false);
                                }

                                var super_admin_user_id = $("#super_admin_user_id").val();
                                $("input[value='" + super_admin_user_id + "']").prop('checked', true);

                                var loggedin_user_id = $("#loggedin_user_id").val();
                                $("input[value='" + loggedin_user_id + "']").prop('checked', true);
                            });
                        }
                    }
                });
            }
        }

        var placeSearch, autocomplete;
        var componentForm = {
            city: 'long_name',
            state: 'long_name',
            country: 'long_name'
        };

        function initAutocomplete() {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
            autocomplete = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */
                    (document.getElementById('job_location')),
                    {types: ['geocode']});

            // When the user selects an address from the dropdown, populate the address
            // fields in the form.
            autocomplete.addListener('place_changed', fillInAddress);

        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();
            for (var component in componentForm) {
                document.getElementById(component).value = '';
                document.getElementById(component).disabled = false;
            }

            // Get each component of the address from the place details
            // and fill the corresponding field on the form in billing.
            try {
                for (var i = 0; i < place.address_components.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    if (addressType == 'locality') {
                        document.getElementById('city').value = place.address_components[i]['long_name'];
                    }
                    if (addressType == 'country') {
                        document.getElementById('country').value = place.address_components[i]['long_name'];
                    }
                    if (addressType == 'administrative_area_level_1') {
                        document.getElementById('state').value = place.address_components[i]['long_name'];
                    }
                }
            }
            catch (exception) {

            }

        }

        // Bias the autocomplete object to the user's geographical location,
        // as supplied by the browser's 'navigator.geolocation' object.

        function geolocate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var geolocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    autocomplete.setBounds(circle.getBounds());
                });
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBX3rfr9axYY2kE1hyBHFNR9ySTSY5Fcag&libraries=places&callback=initAutocomplete" async defer></script>
@endsection