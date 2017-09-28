@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Job Openings List</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('jobopen.create') }}"> Create Job Openings</a>
            </div>

            <div class="pull-right">
                {{--<a class="btn btn-success" href="{{ route('jobopen.create') }}"> Search</a>--}}
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Search</button>

            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Job Search</h4>
                </div>
                {!! Form::open(array('route' => 'jobopen.index','files' => true,'method'=>'POST', 'id' => 'jobsForm')) !!}
                <div class="modal-body">

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                                    <div class="">

                                        <div class="form-group {{ $errors->has('client_id') ? 'has-error' : '' }}">
                                            <strong>Select Client: </strong>
                                            {!! Form::select('client_id', $client,isset($clint_id)?$client_id:null, array('id'=>'client_id','class' => 'form-control')) !!}
                                            @if ($errors->has('client_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('client_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('posting_title') ? 'has-error' : '' }}">
                                            <strong>Posting Title:  </strong>
                                            {!! Form::select('posting_title', $posting_title, array('id'=>'posting_title','placeholder' => 'Posting Title','class' => 'form-control' )) !!}
                                            @if ($errors->has('posting_title'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('posting_title') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('from_date') ? 'has-error' : '' }}">
                                            <strong>From Date:  </strong>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                {!! Form::text('from_date', isset($from_date) ? $from_date : null, array('id'=>'from_date','placeholder' => 'From Date','class' => 'form-control' )) !!}
                                            </div>
                                            @if ($errors->has('from_date'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('from_date') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <strong>City:</strong>
                                            {!! Form::text('city', null, array('id'=>'city','placeholder' => 'City','class' => 'form-control')) !!}
                                        </div>


                                    </div>
                                </div>

                                <div class="box-body col-xs-6 col-sm-6 col-md-6">
                                    <div class="">

                                        <div class="form-group {{ $errors->has('job_id') ? 'has-error' : '' }}">
                                            <strong>Select Job Opening Id: </strong>
                                            {!! Form::select('job_id', $job_open_id,isset($job_openId)?$job_openId:null, array('id'=>'job_id','class' => 'form-control')) !!}
                                            @if ($errors->has('job_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('job_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('job_opening_status') ? 'has-error' : '' }}">
                                            <strong>Select Job Opening Status :</strong>
                                            {!! Form::select('job_opening_status', $job_open_status,null, array('id'=>'job_opening_status','class' => 'form-control')) !!}
                                            @if ($errors->has('job_opening_status'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('job_opening_status') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('to_date') ? 'has-error' : '' }}">
                                            <strong>From Date:  </strong>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                {!! Form::text('to_date', isset($to_date) ? $to_date : null, array('id'=>'to_date','placeholder' => 'To Date','class' => 'form-control' )) !!}
                                            </div>
                                            @if ($errors->has('to_date'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('to_date') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default">Search</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <?php $i = 0; ?>

    @if(isset($jobList) && sizeof($jobList)>0)
        @foreach($jobList as $key=>$value)
            <div class="accordiontable accordiontable-activity panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a id="collapse-{{ $value['client_id'] }}" data-toggle="collapse" class="collapse-{{ $value['client_id'] }}" href="#collapse{{ $value['client_id'] }}">
                                <table>
                                    <tbody>
                                    <tr>
                                        <?php

                                            if($i == 0){
                                                $hide_cell = "hide-cell";
                                                $arrow = "up";
                                                $collapse = "in";
                                            }else{
                                                $hide_cell = "";
                                                $arrow = "down";
                                                $collapse = "";
                                            }

                                        //$hide_cell = "";
                                        //$arrow = "down";
                                        //$collapse = "";
                                        ?>

                                        <td>
                                            @if(isset($value['client_name']))
                                                {{ $value['client_name'] }}
                                            @endif
                                        </td>

                                        <td class="hide-in-collapse {{ $hide_cell }}">
                                            @if(isset($value['client_mail']))
                                                {{ $value['client_mail'] }}
                                            @endif
                                        </td>
                                        <td class="hide-in-collapse {{ $hide_cell }}">
                                            @if(isset($value['client_mobile']))
                                                <b>{{ $value['client_mobile'] }}</b>
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <i class="indicator glyphicon glyphicon-chevron-{{ $arrow }}  pull-right"></i>
                            </a>
                        </h4>
                    </div>

                    <div id="collapse{{ $value['client_id'] }}" class="panel-collapse collapse {{ $collapse }}">
                        <div class="panel-body">
                            <div class="infocol infocol-1">
                                <table class="datatable">
                                    <tr>
                                        <td class="cell-label">Client Name</td>
                                        @if(isset($value['client_name']))
                                            <td class="cell-data">{{ $value['client_name'] }}</td>
                                        @endif
                                    </tr>

                                    <tr>
                                        <td class="cell-label">Mail</td>
                                        @if(isset($value['client_mail']))
                                            <td class="cell-data">{{ $value['client_mail'] }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="cell-label">Mobile</td>
                                        @if(isset($value['client_mobile']))
                                            <td class="cell-data">{{ $value['client_mobile'] }}</td>
                                        @endif
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <div class="responsive-tbl">
                                <?php $j=0;?>
                                <?php if(isset($value['clientJobs']) && sizeof($value['clientJobs'])>0){ ?>
                                    <table class="sub-activity-table table table-striped responsive">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Job Opening ID</th>
                                            <th>Posting Title</th>
                                            <th>Target Date</th>
                                            <th>Job Opening Status</th>
                                            <th>City</th>
                                            <th width="280px">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($value['clientJobs'] as $k=>$v)
                                            <tr>
                                                <td>{{ ++$j }}</td>
                                                <td>{{ $v['job_id'] }}</td>
                                                <td>{{ $v['posting_title'] }}</td>
                                                <td>{{ $v['target_date'] }}</td>
                                                <td>{{ $v['job_opening_status'] }}</td>
                                                <td>{{ $v['city'] }}</td>
                                                <td>
                                                    <a class="btn btn-info" href="{{ route('jobopen.show',$v['id']) }}">Show</a>
                                                    <a class="btn btn-primary" href="{{ route('jobopen.edit',$v['id']) }}">Edit</a>
                                                    <?php $jobDetails = \App\JobOpen::find($v['id']); ?>
                                                    @include('adminlte::partials.deleteModal', ['data' => $jobDetails, 'name' => 'jobopen','display_name'=>'Job Openings'])
                                                </td>
                                                <?php //$j++; ?>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <?php $i++; ?>
        @endforeach
    @else
        <div class="accordiontable panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="#collapse{{ $i }}">
                            <table>
                                <tbody>
                                <tr>
                                    <td class="hide-in-collapse">
                                        No Jobs
                                    </td>

                                    <td>&nbsp;&nbsp;&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                            <i class="indicator glyphicon glyphicon-chevron-up  pull-right"></i>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
    @endif


@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function(){

        });
        function toggleChevron(e) {

            $(e.target)
                    .prev('.panel-heading')
                    .find("i.indicator")
                    .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');

            $(e.target).prev('.panel-heading').find('table .hide-in-collapse').toggleClass('hide-cell');
            if($(e.target).prev('.panel-heading').find("i.indicator").hasClass('glyphicon-chevron-down')){
                jQuery('.panel-collapse.collapse').removeClass('in');
            }
        }
        $('.panel-group').on('hidden.bs.collapse', toggleChevron);
        $('.panel-group').on('shown.bs.collapse', toggleChevron);
    </script>
@endsection
