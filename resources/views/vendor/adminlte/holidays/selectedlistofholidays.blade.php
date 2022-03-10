@extends('adminlte::page')

@section('title', 'List of Selected Holidays')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h3>Holidays List</h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('listof.holidays',$uid) }}">Back</a>
            </div>
        </div>
    </div>
    
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header with-border col-md-12"></div><br/>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <table border="1" cellpadding="0" cellspacing="0" width="500" style="font-family:Helvetica,Arial,sans-serif;" align="center">
                        <tr>
                            <td width="500" style="font-family:Cambria, serif;">
                                <table width="500" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                    <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 14.0pt;">
                                        <td align="center" style="border: 1px solid black;width: 80px;"><b>Sr. No.</b></td>
                                        <td align="center" style="border: 1px solid black;"><b>Fixed Holiday Leaves</b></td>
                                    </tr>

                                    <?php $i=0; ?>
                                    @foreach($fixed_holiday_list as $key => $value)
                                        <tr style="font-family:Cambria, serif;font-size: 12.0pt;">
                                            <td align="center">{{ ++$i }}</td>
                                            <td align="left" style="padding-left:10px;">
                                            {{ $value['title'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <table border="1" cellpadding="0" cellspacing="0" width="500" style="font-family:Helvetica,Arial,sans-serif;" align="center">
                        <tr>
                            <td width="500" style="font-family:Cambria, serif;">
                                <table width="500" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                    <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 14.0pt;">
                                        <td align="center" style="border: 1px solid black;width: 80px;"><b>Sr. No.</b></td>
                                        <td align="center" style="border: 1px solid black;"><b>Selected Optional Holiday Leaves</td>
                                    </tr>

                                    <?php $i=0; ?>
                                    @foreach($optional_holiday_list as $key => $value)

                                        @if($value['title'] != 'Any other Religious Holiday for respective community - Please specify')
                                            <tr style="font-family:Cambria, serif;font-size: 12.0pt;">
                                                <td align="center">{{ ++$i }}</td>
                                                <td align="left" style="padding-left: 10px;">
                                                {{ $value['title'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    @if(isset($specific_day) && $specific_day != '')
                                        <tr style="font-family:Cambria, serif;font-size: 12.0pt;">
                                            <td align="center">{{ ++$i }}</td>
                                            <td align="left" style="padding-left: 10px;">{{ $specific_day }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <a class="btn btn-primary" href="{{ route('listof.holidays',$uid) }}">Modify Optional Holiday List</a>
        </div>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() { 
        });
    </script>
@endsection