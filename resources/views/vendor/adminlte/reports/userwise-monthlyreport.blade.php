@extends('adminlte::page')

@section('title', 'Monthly Report')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Monthly Report</h2>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box-body col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    {{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control'))}}
                </div>
            </div>

            <div class="box-body col-xs-3 col-sm-3 col-md-3">
                <div class="form-group">
                    {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
                </div>
            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <table border="1" cellpadding="0" cellspacing="0" style="text-align: center;" width="75%" id="userwise-monthly-report">
                <thead>
                    <tr style="background-color: #f39c12;font-weight: bold;">
                        <td>Sr. No.</td>
                        <td>User</td>
                        <td>No. of Cvs Associated</td>
                        <td>No. of Interviews Attended</td>
                        <td>No. of leads added</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; ?>
                    @foreach($response as $k=>$v)
                    <tr>
                        <td>{!! $i !!}</td>
                        <td>{!! $v['uname'] !!}</td>
                        <td>{!! $v['cvs'] !!}</td>
                        <td>{!! $v['interviews'] !!}</td>
                        <td>{!! $v['leadcount'] !!}</td>
                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop

@section('customscripts')
    <script type="text/javascript">

        $(document).ready(function(){
            $("#users_id").select2();
            $('#userwise-monthly-report').DataTable({
                "pageLength": 100
            });
        });

        function select_data(){
            var users_id = $("#users_id").val();
            var month = $("#month").val();
            var year = $("#year").val();

            var url = '/userwise-monthly-report';

            var form = $('<form action="'+url+ '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="month" value="'+month+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }

    </script>
@endsection