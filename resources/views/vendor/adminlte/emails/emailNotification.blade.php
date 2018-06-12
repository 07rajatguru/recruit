<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
@yield('style')

</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">

@foreach($mail as $key => $value)

@if($value['module'] == 'Job Open')
	<table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
    	<tr>
        	<td>
            	<table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: rgba(157,92,172,0.9); height: 70px;">
                	<tr>
                    	<td align="center">
                        	Adler Talent Solutions Pvt. Ltd.
                    	</td>
                	</tr>
            	</table>
        	</td>
    	</tr>
    	<tr>
    		<td>
			<table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
    		<td colspan="7">
                <h3>Basic Information</h3>
            </td>
                <tr>
                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Posting Title</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{!! $job['posting_title'] !!}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Job Opening ID</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $job['job_id'] }}</td>
			    </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Client Name</th>
                    <td align="center" colspan="3" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $job['client_name'] }}</td>
                    {{-- <th>Job Opening Status</th>
                    <td>{{ $job['job_opening_status'] }}</td> --}}
                </tr>
                <tr>
                   	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Hiring Manager</th>
			        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $job['hiring_manager_name'] }}</td>
            		<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Number of Positions</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $job['no_of_positions'] }}</td>
                </tr>
                <tr>
                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Target Date</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $job['target_date'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Date Opened</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $job['date_opened'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Job Type</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $job['job_type'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Industry</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $job['industry_name'] }}</td>
                </tr>
                <tr>
		            <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Work Experience (In years)</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $job['work_experience'] }}</td>
			        <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Salary</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $job['salary'] }}</td>
                </tr>
                <tr>
                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Job Description</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;" colspan="3">{{ $job['description'] }}</td>
                </tr>
                <tr>
		            <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Desired Candidates</th>
                	<td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;" colspan="3">{{ $job['desired_candidate'] }}</td>
                </tr>
				<tr>
                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Users who can access the job</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;" colspan="3">{{ implode(",",$job['users']) }}</td>
                </tr>
                <tr>
                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">Education Qualification</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;" colspan="3">{{ $job['education_qualification']}}</td>
                </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
    	
            <td colspan="7">
			 	<h3>Job Location</h3>
			</td>
               
            	<tr>
                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">Country</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">{{ $job['country'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">State</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">{{ $job['state'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">City</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;">{{ $job['city'] }}</td>
                </tr>
            </table>
        	</td>
        </tr>
                 

        {{--<div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                                <div class="box-header  col-md-6 ">
                                    <h3 class="box-title">Attachments</h3>
                                    &nbsp;&nbsp;
                                    @include('adminlte::jobopen.upload', ['data' => $jobopen, 'name' => 'jobopen'])
                                </div>
                
                                <div class="box-header  col-md-8 ">
                
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
                                        @if(sizeof($jobopen['doc'])>0)
                                            @foreach($jobopen['doc'] as $key=>$value)
                                                <tr>
                                                    <td>
                                                        <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                                        &nbsp;
                                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $jobopen['id'], 'name' => 'jobopenattachments' ,'display_name'=> 'Attachments'])
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
                        </div>--}}
        <input type="hidden" id="token" value="{{ csrf_token() }}">
	</tr>
		<tr style="height: 45px; background-color: #dddddd;">
        	<td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
    	</tr>
	</table>
@else
	{!! $value['message'] !!}
@endif
@endforeach
</body>
</html>