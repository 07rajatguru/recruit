@extends('adminlte::page')

@section('title', 'List of Holidays')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <br/><br/>
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
                                            <td align="left" style="padding-left:10px;">{{ $value['title'] }}</td>
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
                                        <td align="center" style="border: 1px solid black;"><b>Optional Holiday Leaves<br/>(please select any 3 holidays from the list)</b></td>
                                    </tr>

                                    <?php $i=0; ?>
                                    @foreach($optional_holiday_list as $key => $value)

                                        <?php
                                            $data = App\HolidaysUsers::checkUserHoliday($uid,$value['id']);
                                        ?>

                                        <tr style="font-family:Cambria, serif;font-size: 12.0pt;">
                                            <td align="center">{{ ++$i }}</td>

                                            @if($value['title'] == 'Any other Religious Holiday for respective community - Please specify' && isset($data) && $data != '')
                                                <td align="left" style="padding-left: 10px;">
                                                    <input type="checkbox" value="{{ $value['id'] }}" id="holiday_{{ $value['id'] }}" checked="" class="others_holiday" />{{ $value['title'] }}
                                                    <input type="text" name="religious_holiday" id="religious_holiday"><br/>
                                                </td>
                                            @elseif(isset($data) && $data != '')
                                                <td align="left" style="padding-left: 10px;">
                                                    <input type="checkbox" value="{{ $value['id'] }}" id="holiday_{{ $value['id'] }}" checked="" class="others_holiday"/>{{ $value['title'] }}
                                                </td>
                                            @else

                                                @if($value['title'] == 'Any other Religious Holiday for respective community - Please specify')
                                                    <td align="left" style="padding-left: 10px;">
                                                    <input type="checkbox" value="{{ $value['id'] }}" id="holiday_{{ $value['id'] }}" class="others_holiday">
                                                    {{ $value['title'] }}
                                                    <input type="text" name="religious_holiday" id="religious_holiday"><br/>
                                                </td>
                                                @else
                                                    <td align="left" style="padding-left: 10px;">
                                                        <input type="checkbox" value="{{ $value['id'] }}" id="holiday_{{ $value['id'] }}" class="others_holiday">{{ $value['title'] }}
                                                    </td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </table><br/>
                </div>
            </div>
        </div>
    </div><br/>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary" id="leave_form" onclick="submitForm();">Submit</button>
        </div>
    </div>

    <input type="hidden" name="selected_leaves" id="selected_leaves" value="">
    <input type="hidden" name="length" id="length" value="{{ $length }}">
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            $('input[type=checkbox]:not(:checked)').each(function(){

                var id = $(this).attr("value");
                $("#holiday_"+id).attr("disabled", true);
            });

            var $checkboxes = $('.others_holiday').change(function() {

                var selected_length = $('.others_holiday:checked').length;
                var actual_length = $("#length").val();

                if(selected_length == actual_length) {

                    $checkboxes.filter(':not(:checked)').prop('disabled', true);
                }
                else {

                    $checkboxes.filter(':not(:checked)').prop('disabled', false);
                }

                if ($(this).prop('checked')) {

                    var array = [];
                    var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')

                    for (var i = 0; i < checkboxes.length; i++) {
                        array.push(checkboxes[i].value);
                    }

                    $("#selected_leaves").val(array);
                }
            });
        });

        function submitForm() {

            var religious_holiday = $("#religious_holiday").val();
            var selected_leaves = $("#selected_leaves").val();
            var token = $('input[name="csrf_token"]').val();
            var app_url = "{!! env('APP_URL'); !!}";

            if(selected_leaves != '') {

                $.ajax({

                    type : 'POST',
                    url : app_url+'/holidays/sentholidays',
                    data : {religious_holiday : religious_holiday,selected_leaves : selected_leaves, '_token':token},
                    dataType : 'json',
                    success: function(msg) {

                        if (msg.success == 'Success') {

                            alert("Email Sent Successfully.");
                            window.location.reload();
                        }
                    }
                });
            }
            else {

                alert("Please Select Leave.");
                return false;
            }
        }
    </script>
@endsection