@extends('adminlte::page')

@section('title', 'Productivity Report')

@section('content_header')
    <h1></h1>
@stop

@section('content')
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="col-md-12">
                <div class="col-md-4">
                    <h2>Productivity Report</h2>
                </div>
            </div>
        </div>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="col-md-12">
                <div class="col-md-1">
                    <select class="form-control" name="month" id="month">
                        @foreach($month_array as $key=>$value)
                            <option value={{ $key }} @if($key==$month) selected="selected" @endif>{{ $value}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <select class="form-control" name="year" id="year">
                        @foreach($year_array as $key=>$value)
                            <option value={{ $key }} @if($key==$year) selected="selected" @endif>{{ $value}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <input class="btn btn-primary" type="submit" value="Select" onClick="select_data()" style="width:80px;"/>
                </div>

                <div class="col-md-5"></div>
                <div class="col-md-2">
                    <!-- <a class="btn btn-success btn-block" href="javascript:void(0);" onClick="export_data()">Download as Excel</a> -->
                </div>
                 <div class="col-md-2">
                    <a class="btn btn-success btn-block" href="javascript:void(0);" onClick="sendMail()">Send Email</a>
                </div>
            </div>
        </div>
    </div>

    @if(isset($bench_mark) && sizeof($bench_mark) > 0)

    <input type="hidden" name="no_of_weeks" id="no_of_weeks" value="{{ $no_of_weeks }}">
    <div style="padding: 10px;">
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" style="width: 99.80%;border-collapse: collapse;" id="productivity_report_table">
                <thead></thead>
                <tbody>
                    <tr style="height: 15px;">
                        <td colspan="11" valign="bottom" style="border: solid black 2px;background: rgb(70,189,198);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">

                            <?php 
                                $full_year =  $year;
                                $year_display = substr($full_year, -2);
                                $month_display = date('F', mktime(0, 0, 0, $month, 10));

                                // For Set Limegreen & Red Color
                                $date = date('l');
                                $from_date_default = date('Y-m-d',strtotime("$date monday this week"));
                                $to_date_default = date('Y-m-d',strtotime("$from_date_default +6days"));
                            ?>

                            <p align="center" style="text-align: center;">
                                <b><span style="font-size: 28px;color: black;">Productivity Report - {{ $month_display }}' {{ $year_display }}</span></b>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td rowspan="2" valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;width: 20px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Sr. No.</span></b></p>
                        </td>
                        <td rowspan="2" valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;width: 200px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Particular</span></b></p>
                        </td>
                        <td width="122" rowspan="2" valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15.75pt;width: 125.25pt;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Minimum % / Benchmark</span></b><b><span></p>
                        </td>
                        <td width="122" rowspan="2" valign="bottom" style="width: 105.25pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15.75pt;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Standard Numbers / Monthly</span></b></p>
                        </td>
                        <td width="122" rowspan="2" valign="bottom" style="width: 105.25pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15.75pt;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Standard Numbers / Weekly</span></b></p>
                        </td>
                        <td width="217" rowspan="2" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;display: none;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Standard Numbers / Daily</span></b></p>
                        </td>
                        <td width="220" colspan="5" valign="bottom" style="width: 164.7pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: rgb(191,191,191);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Actual Weekly Numbers</span></b></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid black 2px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                    </tr>
                    <tr style="height: 30px;">
                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) > 0)
                        <?php $i = 0; ?>
                            @foreach($frm_to_date_array as $key => $value)
                                <?php
                                    $from_date = date('d/m',strtotime($value['from_date']));
                                    $to_date = date('d/m',strtotime($value['to_date']));
                                ?>

                                @if($i == 0)
                                    <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;width: 65px;">
                                        <p align="center" style="text-align: center;"><b><span style="color: black;">Week1</span></b><br/>
                                            <b>{{ $from_date }} to {{ $to_date }}</b>
                                        </p>
                                    </td>
                                @endif

                                @if($i == 1)
                                    <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;width: 65px;">
                                        <p align="center" style="text-align: center;"><b><span style="color: black;">Week2</span></b><br/>
                                            <b>{{ $from_date }} to {{ $to_date }}</b>
                                        </p>
                                    </td>
                                @endif

                                @if($i == 2)
                                    <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;width: 65px;">
                                        <p align="center" style="text-align: center;"><b><span style="color: black;">Week3</span></b><br/>
                                            <b>{{ $from_date }} to {{ $to_date }}</b>
                                        </p>
                                    </td>
                                @endif

                                @if($i == 3)
                                    <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;width: 65px;">
                                        <p align="center" style="text-align: center;"><b><span style="color: black;">Week4</span></b><br/>
                                            <b>{{ $from_date }} to {{ $to_date }}</b>
                                        </p>
                                    </td>
                                @endif

                                @if($i == 4)
                                    <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;width: 65px;">
                                        <p align="center" style="text-align: center;"><b><span style="color: black;">Week5</span></b><br/>
                                            <b>{{ $from_date }} to {{ $to_date }}</b>
                                        </p>
                                    </td>
                                @endif
                                <?php $i = $i + 1; ?>
                            @endforeach
                        @endif

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) == '4')
                            <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 2px;background: rgb(241,194,50);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;width: 65px;">
                            </td>
                        @endif

                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 2px;background: rgb(191,191,191);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;width: 20px;">
                            <p align="center" style="text-align: center;"><b><span style="color: rgb(153,0,0);">Monthly Achievement</span></b></p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>1</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Number of Resumes delivered to client</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>NA</span></p>
                        </td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: rgb(234,209,220);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="no_of_resumes_monthly">
                            <p align="center" style="text-align: center;">
                                <span style="color: black;">{{ $bench_mark['no_of_resumes_monthly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span style="color: black;">{{ $bench_mark['no_of_resumes_weekly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="217" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;display: none;">
                            <p align="center" style="text-align: center;">
                                <span style="color: black;">{{ $bench_mark['no_of_resumes_daily'] }}
                                </span>
                            </p>
                        </td>

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) > 0)
                            @foreach($frm_to_date_array as $key => $value)

                                <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                                @if($from_date > $to_date_default)

                                    <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="no_of_resumes_weeks">
                                        <p align="center" style="text-align: center;"></p>
                                    </td>
                                @else

                                    @if($value['ass_cnt'] >= $bench_mark['no_of_resumes_weekly'])
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: limegreen;" class="no_of_resumes_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['ass_cnt'] }}</span>
                                            </p>
                                        </td>
                                    @else
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: red;" class="no_of_resumes_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['ass_cnt'] }}</span>
                                            </p>
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) == '4')
                            <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="no_of_resumes_weeks">
                                <p align="center" style="text-align: center;"><span>NA</span></p>
                            </td>
                        @endif
                        
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="no_of_resumes_monthly_achievement"></span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>2</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Shortlist Ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="shortlist_ratio">
                            <p align="center" style="text-align: center;">
                                <span>50% (of Total CVs)</span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="shortlist_ratio_monthly">
                                    {{ $bench_mark['shortlist_ratio_monthly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="shortlist_ratio_weekly">
                                    {{ $bench_mark['shortlist_ratio_weekly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="217" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;display: none;">
                            <p align="center" style="text-align: center;">
                                <span class="shortlist_ratio_daily">
                                    {{ $bench_mark['shortlist_ratio_daily'] }}
                                </span>
                            </p>
                        </td>
                        
                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) > 0)
                            @foreach($frm_to_date_array as $key => $value)
                                
                                <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                                @if($from_date > $to_date_default)
                                
                                    <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="shortlist_ratio_weeks">
                                        <p align="center" style="text-align: center;"></p>
                                    </td>
                                @else

                                    @if($value['shortlisted_cnt'] >= $bench_mark['shortlist_ratio_weekly'])
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: limegreen;" class="shortlist_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['shortlisted_cnt'] }}</span>
                                            </p>
                                        </td>
                                    @else
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: red;" class="shortlist_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['shortlisted_cnt'] }}</span>
                                            </p>
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) == '4')
                            <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="shortlist_ratio_weeks">
                                <p align="center" style="text-align: center;"><span>NA</span></p>
                            </td>
                        @endif
                       
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="shortlist_ratio_monthly_achievement"></span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>3</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Interview Ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="interview_ratio">
                            <p align="center" style="text-align: center;">
                                <span>50% (Shortlist Ratio)</span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="interview_ratio_monthly">
                                    {{ $bench_mark['interview_ratio_monthly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="interview_ratio_weekly">
                                    {{ $bench_mark['interview_ratio_weekly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="217" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;display: none;">
                            <p align="center" style="text-align: center;">
                                <span class="interview_ratio_daily">
                                    {{ $bench_mark['interview_ratio_daily'] }}
                                </span>
                            </p>
                        </td>
                        
                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) > 0)
                            @foreach($frm_to_date_array as $key => $value)
                                
                                <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>
                                
                                @if($from_date > $to_date_default)

                                    <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="interview_ratio_weeks">
                                        <p align="center" style="text-align: center;"></p>
                                    </td>
                                @else

                                    @if($value['interview_cnt'] >= $bench_mark['interview_ratio_weekly'])
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: limegreen;" class="interview_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['interview_cnt'] }}</span>
                                            </p>
                                        </td>
                                    @else
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: red;" class="interview_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['interview_cnt'] }}</span>
                                            </p>
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) == '4')
                            <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="interview_ratio_weeks">
                                <p align="center" style="text-align: center;"><span>NA</span></p>
                            </td>
                        @endif
                       
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="interview_ratio_monthly_achievement"></span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>4</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Selection Ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="selection_ratio">
                            <p align="center" style="text-align: center;">
                                <span>20% (of Interview Ratio)</span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="selection_ratio_monthly">
                                    {{ $bench_mark['selection_ratio_monthly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height:15px;">
                            <p align="center" style="text-align: center;">
                                <span class="selection_ratio_weekly">
                                    {{ $bench_mark['selection_ratio_weekly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="217" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height:15px;display: none;">
                            <p align="center" style="text-align: center;">
                                <span class="selection_ratio_daily">
                                    {{ $bench_mark['selection_ratio_daily'] }}
                                </span>
                            </p>
                        </td>
                       
                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) > 0)
                            @foreach($frm_to_date_array as $key => $value)
                                
                                <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                                @if($from_date > $to_date_default)

                                    <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="selection_ratio_weeks">
                                        <p align="center" style="text-align: center;"></p>
                                    </td>
                                @else

                                    @if($value['selected_cnt'] >= $bench_mark['selection_ratio_weekly'])
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: limegreen;" class="selection_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['selected_cnt'] }}</span>
                                            </p>
                                        </td>
                                    @else
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: red;" class="selection_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['selected_cnt'] }}</span>
                                            </p>
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) == '4')
                            <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="selection_ratio_weeks">
                                <p align="center" style="text-align: center;"><span>NA</span></p>
                            </td>
                        @endif
                        
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="selection_ratio_monthly_achievement"></span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>5</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Offer Acceptance Ratio
                            </span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="offer_acceptance_ratio">
                            <p align="center" style="text-align: center;">
                                <span>70% (of Selection Ratio)</span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="offer_acceptance_ratio_monthly">
                                    {{ $bench_mark['offer_acceptance_ratio_monthly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="offer_acceptance_ratio_weekly">
                                    {{ $bench_mark['offer_acceptance_ratio_weekly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="217" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;display: none;">
                            <p align="center" style="text-align: center;">
                                <span class="offer_acceptance_ratio_daily">
                                    {{ $bench_mark['offer_acceptance_ratio_daily'] }}
                                </span>
                            </p>
                        </td>

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) > 0)
                            @foreach($frm_to_date_array as $key => $value)

                                <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                                @if($from_date > $to_date_default)

                                    <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="offer_acceptance_ratio_weeks">
                                        <p align="center" style="text-align: center;"></p>
                                    </td>
                                @else

                                    @if($value['offer_acceptance_ratio'] >= $bench_mark['offer_acceptance_ratio_weekly'])
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: limegreen;" class="offer_acceptance_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['offer_acceptance_ratio'] }}</span>
                                            </p>
                                        </td>
                                    @else
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: red;" class="offer_acceptance_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['offer_acceptance_ratio'] }}</span>
                                            </p>
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) == '4')
                            <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="offer_acceptance_ratio_weeks">
                                <p align="center" style="text-align: center;"><span>NA</span></p>
                            </td>
                        @endif

                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="offer_acceptance_ratio_monthly_achievement"></span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>6</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Joining Ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="joining_ratio">
                            <p align="center" style="text-align: center;">
                                <span>80% (of offer acceptance)</span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="joining_ratio_monthly">
                                    {{ $bench_mark['joining_ratio_monthly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p  align="center" style="text-align: center;">
                                <span class="joining_ratio_weekly">
                                    {{ $bench_mark['joining_ratio_weekly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="217" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;display: none;">
                            <p  align="center" style="text-align: center;">
                                <span class="joining_ratio_daily">
                                    {{ $bench_mark['joining_ratio_daily'] }}
                                </span>
                            </p>
                        </td>

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) > 0)
                            @foreach($frm_to_date_array as $key => $value)

                                <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                                @if($from_date > $to_date_default)

                                    <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="joining_ratio_weeks">
                                            <p align="center" style="text-align: center;"></p>
                                    </td>
                                @else

                                    @if($value['joining_ratio'] >= $bench_mark['joining_ratio_weekly'])
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: limegreen;" class="joining_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['joining_ratio'] }}</span>
                                            </p>
                                        </td>
                                    @else
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: red;" class="joining_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['joining_ratio'] }}</span>
                                            </p>
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) == '4')
                            <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="joining_ratio_weeks">
                                <p align="center" style="text-align: center;"><span>NA</span></p>
                            </td>
                        @endif

                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="joining_ratio_monthly_achievement"></span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>7</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>After Joining Success Ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="after_joining_success_ratio">
                            <p align="center" style="text-align: center;">
                                <span>80% (Joining Ratio)</span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="after_joining_success_ratio_monthly">
                                    {{ $bench_mark['after_joining_success_ratio_monthly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="122" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="after_joining_success_ratio_weekly">
                                    {{ $bench_mark['after_joining_success_ratio_weekly'] }}
                                </span>
                            </p>
                        </td>
                        <td width="217" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;display: none;">
                            <p align="center" style="text-align: center;">
                                <span class="after_joining_success_ratio_daily">
                                    {{ $bench_mark['after_joining_success_ratio_daily'] }}
                                </span>
                            </p>
                        </td>

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) > 0)
                            @foreach($frm_to_date_array as $key => $value)
                                
                                <?php $from_date = date('Y-m-d',strtotime($value['from_date'])); ?>

                                @if($from_date > $to_date_default)

                                    <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;border-bottom: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="after_joining_success_ratio_weeks">
                                        <p align="center" style="text-align: center;"></p>
                                    </td>
                                @else

                                    @if($value['joining_success_ratio'] >= $bench_mark['after_joining_success_ratio_weekly'])
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;border-bottom: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: limegreen;" class="after_joining_success_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['joining_success_ratio'] }}</span>
                                            </p>
                                        </td>
                                    @else
                                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;border-bottom: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: red;" class="after_joining_success_ratio_weeks">
                                            <p align="center" style="text-align: center;">
                                                <span>{{ $value['joining_success_ratio'] }}</span>
                                            </p>
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if(isset($frm_to_date_array) && sizeof($frm_to_date_array) == '4')
                            <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;border-bottom: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;" class="after_joining_success_ratio_weeks">
                                <p align="center" style="text-align: center;"><span>NA</span></p>
                            </td>
                        @endif

                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;border-bottom: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <span class="after_joining_success_ratio_monthly_achievement"></span>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border: solid rgb(204,204,204) 1px;border-top: none;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;border-left: solid black 2px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td width="122" valign="bottom" colspan="2" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;background-color: rgb(70,189,198);border: solid black 2px;">
                            <center>
                                <b>Overall Feedback (Meet Expectation / Improvements Needed)</b>
                            </center>
                        </td>
                        
                        <td width="74" valign="bottom" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border: solid rgb(204,204,204) 1px;border-top: none;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;border-left: solid black 2px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td width="74" valign="bottom" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                    </tr>
                    <tr style="height: 15.75pt;border: solid black 2px;">
                        <td colspan="11" rowspan="16" valign="bottom" style="border: solid black 2px;border-top: none;background: rgb(234,209,220);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15.75pt;">
                            <p>
                                <b><u><span style="font-family: Arial, sans-serif;color: rgb(204,65,37);"><br /><br />Important Notes: </span></u></b>
                                <b><u><span style="font-family: Arial, sans-serif;color: rgb(17,85,204);"><br /></span></u></b>
                                <b><u><span style="font-size: 12.0pt;font-family: Arial, sans-serif;color: rgb(116,27,71);"><br /></span></u></b>
                                <span style="font-size: 12.0pt;color: black;">
                                    1) Weekly data is generated Auto Generated by Easy2hire based on actions made on software starting from associating resume to after joining success ratio.<br />
                                    2) Please add a comment if that is important to justify weekly numbers. The remarks column is given for overall feedback of monthly performance / action points for coming month.<br />
                                    3) Weekly Overall feedback will depend upon Standard Numbers Vs Achievement. Minimum 2 criteria's (out of first 6) should ideally meet expectation on weekly basis.<br />
                                    4) Performance for the current month will lead to Benefits OR Efforts to improve (Penalties) for the next month. 
                                </span>
                                <span style="font-size: 12.0pt;color: rgb(116,27,71);"><br /></span>
                                <b>
                                    <span style="font-size: 12.0pt;color: black;">
                                        5) Monthly achievement will lead to Reflection Process.
                                    </span>
                                </b>
                                <span style="font-size: 12.0pt;color: black;"> <br />
                                    a. Weightage percentage to be given between (80-100%) in case monthly productivity report is meet expectation / exceeds expectation / exceptional.<br />
                                    b. Weightage percentage to be given between (50-80%) in case monthly productivity report is improvements needed.<br />
                                    c. Weightage percentage to be given between (Less than 50%) in case monthly productivity report is unsatisfactory.
                                </span>
                                <b>
                                    <u><span style="font-family: Arial, sans-serif;color: rgb(204,65,37);"></span></u>
                                </b>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
@stop

@section('customscripts')
	<script type="text/javascript">
		$(document).ready(function() {

            $("#month").select2({width : '90px'});
            $("#year").select2({width : '90px'});

            // Call Function for calculate ratio
            calCulation();
           
			var table = jQuery("#productivity_report_table").DataTable({
				responsive: true,
				"pageLength": 100,
				stateSave: true
			});
            
			if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
		});

        function select_data() {

            var app_url = "{!! env('APP_URL'); !!}";
            var month = $("#month").val();
            var year = $("#year").val();

            var url = app_url+'/master-productivity-report';

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="hidden" name="month" value="'+month+'" />' +
                '<input type="hidden" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }

        function sendMail() {

            var app_url = "{!! env('APP_URL'); !!}";
            var month = $("#month").val();
            var year = $("#year").val();

            var url = app_url+'/master-productivity-report';

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="hidden" name="month" value="'+month+'" />' +
                '<input type="hidden" name="year" value="'+year+'" />' +
                '<input type="hidden" name="mail" value="send" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }

        // For Display Dynamic data in last cell
        function calCulation() {

            // For No of Resumes
            var no_of_resumes_weeks = 0;

            $('.no_of_resumes_weeks').each(function() {

                if($(this).text() > 0) {
                    no_of_resumes_weeks += parseInt($(this).text());
                }
            });

            if(no_of_resumes_weeks > 0) {
                $(".no_of_resumes_monthly_achievement").text(no_of_resumes_weeks);
            }

            // For Shortlist Ratio
            var shortlist_ratio_weeks = 0;

            $('.shortlist_ratio_weeks').each(function() {

                if($(this).text() > 0) {
                    shortlist_ratio_weeks += parseInt($(this).text());
                }
            });

            if(shortlist_ratio_weeks > 0) {
                $(".shortlist_ratio_monthly_achievement").text(shortlist_ratio_weeks);
            }

            // For Interview Ratio
            var interview_ratio_weeks = 0 ;

            $('.interview_ratio_weeks').each(function() {

                if($(this).text() > 0) {
                    interview_ratio_weeks += parseInt($(this).text());
                }
            });

            if(interview_ratio_weeks > 0) {
                $(".interview_ratio_monthly_achievement").text(interview_ratio_weeks);
            }

            // For Selection Ratio
            var selection_ratio_weeks = 0;

            $('.selection_ratio_weeks').each(function() {

                if($(this).text() > 0) {
                    selection_ratio_weeks += parseInt($(this).text());
                }
            });

            if(selection_ratio_weeks > 0) {
                $(".selection_ratio_monthly_achievement").text(selection_ratio_weeks);
            }

            // For Offer Acceptance Ratio
            var offer_acceptance_ratio_weeks = 0;

            $('.offer_acceptance_ratio_weeks').each(function() {

                if($(this).text() > 0) {
                    offer_acceptance_ratio_weeks += parseInt($(this).text());
                }
            });

            if(offer_acceptance_ratio_weeks > 0) {
                $(".offer_acceptance_ratio_monthly_achievement").text(offer_acceptance_ratio_weeks);
            }

            // For Joining Ratio
            var joining_ratio_weeks = 0;

            $('.joining_ratio_weeks').each(function() {

                if($(this).text() > 0) {
                    joining_ratio_weeks += parseInt($(this).text());
                }
            });

            if(joining_ratio_weeks > 0) {
                $(".joining_ratio_monthly_achievement").text(joining_ratio_weeks);
            }

            // For After Joining Success Ratio
            var after_joining_success_ratio_weeks = 0;

            $('.after_joining_success_ratio_weeks').each(function() {

                if($(this).text() > 0) {
                    after_joining_success_ratio_weeks += parseInt($(this).text());
                }
            });

            if(after_joining_success_ratio_weeks > 0) {
                $(".after_joining_success_ratio_monthly_achievement").text(after_joining_success_ratio_weeks);
            }
        }
	</script>
@endsection