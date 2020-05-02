@extends('adminlte::page')

@section('title', 'Weekly Goal Sheet')

@section('content_header')
    <h1></h1>
@stop

@section('content')
	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Weekly Goal Sheet</h2>
            </div>
        </div>
    </div>

    <div style="padding: 10px;">
        <div class="table-responsive">
            <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;border-collapse: collapse;" id="weekly_goal_sheet_table">
                <thead></thead>
                <tbody>
                    <tr style="height: 15px;">
                        <td colspan="11" valign="bottom" style="border: solid black 2px;background: rgb(244,176,131);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;">
                                <b><span style="font-size: 28px;color: black;">User Name - Weekly Goal Sheet - March'20</span></b>
                            </p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td rowspan="2" valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Sr. No.</span></b></p>
                        </td>
                        <td rowspan="2" valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Particular</span></b></p>
                        </td>
                        <td rowspan="2" valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15.75pt;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Minimum % / Benchmark</span></b><b><span></p>
                        </td>
                        <td width="122" rowspan="2" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Standard Numbers / Monthly</span></b></p>
                        </td>
                        <td width="217" rowspan="2" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Standard Numbers / Weekly</span></b></p>
                        </td>
                        <td width="220" colspan="4" valign="bottom" style="width: 164.7pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;background: rgb(191,191,191);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Weekly Numbers</span></b></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid black 2px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                        <td rowspan="2" valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 2px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Remarks, If any</span></b></p>
                        </td>
                    </tr>
                    <tr style="height: 30px;">
                        <td width="74" valign="bottom" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Week1</span></b></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Week2</span></b></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Week3</span></b></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 2px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Week4</span></b></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 2px;background: rgb(217,226,243);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 29px;">
                            <p align="center" style="text-align: center;"><b><span style="color: black;">Total</span></b></p>
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>1</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>No of Resumes</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>NA</span></p>
                        </td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: rgb(255,217,101);padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">150</span></p>
                        </td>
                        <td width="217" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>38</span></p>
                        </td>
                        <td width="74" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">30</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">25</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">28</span></p>
                        </td>
                            <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;background: white;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">83</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>2</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Shortlist Ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>50% (of Total CVs)
                            </span></p>
                        </td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>75</span></p>
                        </td>
                        <td width="217" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>19</span></p>
                        </td>
                        <td width="74" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: lime;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">22</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">10</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">9</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;background: white;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">41</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>3</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Interview Ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>50% (Shortlist Ratio)</span></p>
                        </td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>38</span></p>
                        </td>
                        <td width="217" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>9</span></p>
                        </td>
                        <td width="74" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: lime;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">9</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">5</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">8</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: white;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">22</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>4</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Selection Ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>20% (of Interview Ratio)</span></p>
                        </td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>8</span></p>
                        </td>
                        <td width="217" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height:15px;">
                            <p align="center" style="text-align: center;"><span>2</span></p>
                        </td>
                        <td width="74" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">1</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p  align="center" style="text-align: center;"><span style="color: black;">1</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">3</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;background: white;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">5</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
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
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>70% (of Selection Ratio)</span></p>
                        </td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>5</span></p>
                        </td>
                        <td width="217" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>1</span></p>
                        </td>
                        <td width="74" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">0</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">0</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">3</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;background: white;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">3</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>6</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>Joining Ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>80% (of offer acceptance)</span></p>
                        </td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>4</span></p>
                        </td>
                        <td width="217" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p  align="center" style="text-align: center;"><span>1</span></p>
                        </td>
                        <td width="74" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">0</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p  align="center" style="text-align: center;"><span style="color: black;">0</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">0</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;background: white;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">0</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border-top: none;border-left: solid black 2px;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>7</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>After Joining success ratio</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>80% (Joining Ratio)
                            </span></p>
                        </td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>3</span></p>
                        </td>
                        <td width="217" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span>1</span></p>
                        </td>
                        <td width="74" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15.75pt;">
                            <p align="center" style="text-align: center;"><span style="color: black;">0</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;background: red;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="color: black;">0</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">1</span></p>
                        </td>
                        <td style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 2px;background: white;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 1px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                            <p align="center" style="text-align: center;"><span style="font-size: 10.0pt;font-family: Arial, sans-serif;">1</span></p>
                        </td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid black 2px;border-right: solid black 2px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;">
                        </td>
                    </tr>
                    <tr style="height: 15px;">
                        <td valign="bottom" style="border: solid rgb(204,204,204) 1px;border-top: none;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td width="122" valign="bottom" style="width: 91.25pt;border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td width="217" valign="bottom" style="width: 163.05pt;border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td width="74" valign="bottom" style="width: 55.5pt;border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;background: lime;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                        <td valign="bottom" style="border-top: none;border-left: none;border-bottom: solid rgb(204,204,204) 1px;border-right: solid rgb(204,204,204) 1px;padding: 1.5pt 2.25pt 1.5pt 2.25pt;height: 15px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('customscripts')
	<script type="text/javascript">
		$(document).ready(function() {
           
			var table = jQuery("#weekly_goal_sheet_table").DataTable({
				responsive: true,
				"pageLength": 100,
				stateSave: true
			});
			new jQuery.fn.dataTable.FixedHeader( table );
		});
	</script>
@endsection