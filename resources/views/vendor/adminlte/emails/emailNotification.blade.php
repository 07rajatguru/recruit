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
        			    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
                    		<td colspan="7">
                                <h3>Basic Information</h3>
                            </td>
                            <tr>
                            	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Posting Title</th>
                                <td align="center" colspan="3" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                                    {!! $job['posting_title'] !!}</td>
            			    </tr>
                            <tr>
                                <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Client Name</th>
                                <td align="center" colspan="3" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                                    {{ $job['client_name'] }}</td>
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
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                                    {{ $job['industry_name'] }}</td>
                            </tr>
                            <tr>
            		            <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Work Experience (In years)</th>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $job['work_experience'] }}</td>
            			        <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Salary(In Lacs)</th>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $job['salary'] }}</td>
                            </tr>
                            <tr>
                            	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Job Description</th>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;" colspan="3">
                                    {!! $job['description'] !!}</td>
                            </tr>
                            <tr>
            		            <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Desired Candidates</th>
                            	<td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;" colspan="3">
                                    {!! $job['desired_candidate'] !!}</td>
                            </tr>
            				<tr>
                            	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Users who can access the job</th>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;" colspan="3">
                                    {{ implode(",",$job['users']) }}</td>
                            </tr>
                            <tr>
                            	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">Education Qualification</th>
                                <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;" colspan="3">{!! $job['education_qualification'] !!}</td>
                            </tr>
                        </table>

                        <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
            	
                            <td colspan="7">
                                @if(isset($job['remote_working']) && $job['remote_working'] != '')
                                    <h3>Job Location - {{ $job['remote_working'] }}</h3>
                                @else
                                    <h3>Job Location</h3>
                                @endif
                			</td>
                       
                            @if(isset($job['remote_working']) && $job['remote_working'] != '')

                            @else
                            	<tr>
                                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">Country</th>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">{{ $job['country'] }}
                                    </td>
                                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">State</th>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">{{ $job['state'] }}
                                    </td>
                                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">City</th>
                                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;">{{ $job['city'] }}</td>
                                </tr>
                            @endif
                        </table>

            			<table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
            				<tr>
            					<td align="center" style="padding: 0px;">
                                    <a style="border: black; background-color: #9c5cac;color: white;padding: 15px 50px 15px 50px; border-radius: 18px;font-size: 15px;width: 59%;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/jobs/'.$job['module_id']}}">Show</a>
            					</td>
            				</tr>
            			</table>
                    </td>
                </tr>
        	</table>
        @elseif($value['module'] == 'Job Open to All')
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
               <tr>
                    <td width="600">
                        <table cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                            <tr style="background-color:white;">
                                <td align="center">
                                    <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                        <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                            <img width="600" class="site-logo"  src="{{ getenv('APP_URL') }}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;padding-top: 16px; vertical-align: middle;">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr style="background-color:white;"><td colspan="5"></td></tr>
                <tr style="background-color:white;"><td colspan="5"></td></tr>
                <tr style="background-color:white;">
                    <table align="center" width="600px" cellpadding="0" border="1" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
                        <tr style="background-color:white;height: 30px;">
                            <th>Sr No</th>
                            <th>Managed By</th>
                            <th>Company Name</th>
                            <th>Position Title</th>
                            <th>Location</th>
                        </tr>
                        @if(isset($jobs) && sizeof($jobs) > 0)
                            @foreach($jobs as $k => $v)
                                <tr style="background-color:white;height: 30px;">
                                    <td align="center">{{ ++$k }}</td>
                                    <td align="center">{{ $v['user_name'] }}</td>
                                    <td align="center">{{ $v['client_name'] }}</td>
                                    <td align="center">{{ $v['posting_title'] }}</td>
                                    <td align="center">{{ $v['job_location'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </tr>

                <tr style="background-color:white;">
                    <table align="center" width="600px" cellpadding="0" border="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
                        <tr style="background-color:white;height: 30px;">
                            <td align="center" style="font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent {{ date('Y') }}. All rights reserved.</td>
                        </tr>
                    </table>
                </tr>
            </table>
        @else
        	{!! $value['message'] !!}
        @endif
    @endforeach
</body>
</html>