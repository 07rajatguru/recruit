@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1>Attendance</h1>
@stop

@section('content')
    <div class="row">

        <div class="filter_section">
            <div class="month_div col-md-5" >
                <select class="form-control" name="month" id="month">
                    @foreach($month_list as $key=>$value)
                        <option value={{ $key }} @if($key==$month) selected="selected" @endif>{{ $value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="year_div col-md-5">
                <select class="form-control" name="year" id="year">
                    @foreach($year_list as $key=>$value)
                        <option value={{ $key }} @if($key==$year) selected="selected" @endif>{{ $value}}</option>
                    @endforeach
                </select>
            </div>

            <div class="attendance_submit col-md-2"> <input class="btn btn-success btn-block" type="button" value="Filter" name ="filter" id="filter" onClick="filter_data()" /> </div>
            <?php if($isSuperAdmin || $isAccountant || $isAdmin) {?>
            <div class="pull-right col-md-2">
                <a class="btn btn-success btn-block"" href="{{ route('home.export') }}"> Export</a>
            </div>
            <?php   }?>
          
        </div>

        <div class="col-sm-12" style="margin-top:2%;">
            @section ('cotable_panel_body')
                <div style ="overflow-x:scroll;">
                    <table class="table table-bordered" id="attendance_table">

                        @foreach($list as $key=>$value)
                            <tr>
                                <th class="headcol">{{ $key }}
                                    <hr>
                                    Login
                                    <hr>
                                    Logout
                                    <hr>
                                    Total
                                </th>

                                @foreach($value as $key1=>$value1)
                                    <td >
                                        {{ $key1 }}<hr>
                                        {{ $value1['login'] }}<hr>
                                        {{ $value1['logout'] }}<hr>
                                    {{ $value1['total'] }}
                                @endforeach
                            </tr>
                        @endforeach

                    </table>
                </div>
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))
        </div>
    </div>

@stop


@section('customscripts')
    <script type="text/javascript">
        function filter_data(){

            var month = $("#month :selected").val();
            var year = $("#year :selected").val();

            var url = '/home';

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="month" value="'+month+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();


        }

    </script>
@stop