@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif
    <div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
                <h2>Dashboard</h2>
        </div>

       <!--  <div class="pull-right">
            @include('adminlte::partials.login', ['name' => 'dashboard'])
            @include('adminlte::partials.logout', ['name' => 'dashboard'])
        </div>
 -->
    </div>

</div>

@stop

@section('content')


    <div class="row">

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $clientCount or 0 }}</h3>
                    <p>No. of Clients added this month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="client" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $jobCount or 0 }}</h3>
                    <p>No. of Current Job Openings</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="jobs" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3>{{ $associatedCount or 0}}</h3>
                    <p>No. of CVS associated this month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="/associatedcvs" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3> {{ $interviewCount or 0}} </h3>
                    <p>Today's and Tomorrow's Interviews</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="/todaytomorrow" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3> {{$candidatejoinCount}}</h3>
                    <p>Candidate Joining this month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="candidatejoin" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-2 col-xs-4">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3> {{ $interviewAttendCount or 0}} </h3>
                    <p>Interviews attended this month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="/attendedinterview" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Today's and Tomorrow's Interview</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th width="180px">Posting Title</th>
                                <th>Candidate Name</th>
                                <th>Candidate Contact No.</th>
                                <th width="160px">Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($interviews))
                                @foreach($interviews as $interview)
                                    <tr>
                                        <td>{{ $interview->client_name }} - {{ $interview->posting_title }} , {{$interview->city}}</td>
                                        <td>{{ $interview->candidate_fname}} </td>
                                        <td>{{ $interview->contact }}</td>
                                        <td>{{ date('d-m-Y h:i A',strtotime($interview->interview_date)) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">No Interviews for Today</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <a href="interview/create" class="btn btn-sm btn-info btn-flat pull-left">Add New Interview</a>
                    <a href="interview" class="btn btn-sm btn-default btn-flat pull-right">View All Interviews</a>
                </div>
                <!-- /.box-footer -->
            </div>

        </div>
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">To Do's</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th width="180px">Subject</th>
                                <th>Assigned By</th>
                                <th>Assigned To</th>
                                <th>Due Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($toDos) && sizeof($toDos)>0)
                                <?php $i =1; ?>
                                @foreach($toDos as $toDo)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $toDo['subject'] }}</td>
                                        <td>{{ $toDo['am_name'] }}</td>
                                        <td>{{ $toDo['assigned_to'] }}</td>
                                        <td>{{ date('d-m-Y h:i A',strtotime($toDo['due_date'] ))}}</td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">No Task</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <a href="todos/create" class="btn btn-sm btn-info btn-flat pull-left">Add New ToDo's</a>
                    <a href="todos" class="btn btn-sm btn-default btn-flat pull-right">View All ToDo's</a>
                </div>
                <!-- /.box-footer -->
            </div>

        </div>
    </div>
    {{--<div class="row">
        <div class="col-lg-6 col-xs-6">
            <div class="box box-info">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                    <i class="fa fa-envelope"></i>

                    <h3 class="box-title">Quick Email</h3>
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                            <i class="fa fa-times"></i></button>
                    </div>
                    <!-- /. tools -->
                </div>
                <div class="box-body">
                    <form action="#" method="post">
                        <div class="form-group">
                            <input class="form-control" name="emailto" placeholder="Email to:" type="email">
                        </div>
                        <div class="form-group">
                            <input class="form-control" name="subject" placeholder="Subject" type="text">
                        </div>
                        <div>
                            <ul class="wysihtml5-toolbar" style=""><li class="dropdown">
                                    <a class="btn btn-default dropdown-toggle " data-toggle="dropdown">

                                        <span class="glyphicon glyphicon-font"></span>

                                        <span class="current-font">Normal text</span>
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="p" tabindex="-1" href="javascript:;" unselectable="on" class="wysihtml5-command-active">Normal text</a></li>
                                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" tabindex="-1" href="javascript:;" unselectable="on">Heading 1</a></li>
                                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" tabindex="-1" href="javascript:;" unselectable="on">Heading 2</a></li>
                                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h3" tabindex="-1" href="javascript:;" unselectable="on">Heading 3</a></li>
                                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h4" tabindex="-1" href="javascript:;" unselectable="on">Heading 4</a></li>
                                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h5" tabindex="-1" href="javascript:;" unselectable="on">Heading 5</a></li>
                                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h6" tabindex="-1" href="javascript:;" unselectable="on">Heading 6</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <div class="btn-group">
                                        <a class="btn  btn-default" data-wysihtml5-command="bold" title="CTRL+B" tabindex="-1" href="javascript:;" unselectable="on">Bold</a>
                                        <a class="btn  btn-default" data-wysihtml5-command="italic" title="CTRL+I" tabindex="-1" href="javascript:;" unselectable="on">Italic</a>
                                        <a class="btn  btn-default" data-wysihtml5-command="underline" title="CTRL+U" tabindex="-1" href="javascript:;" unselectable="on">Underline</a>

                                        <a class="btn  btn-default" data-wysihtml5-command="small" title="CTRL+S" tabindex="-1" href="javascript:;" unselectable="on">Small</a>

                                    </div>
                                </li>
                                <li>
                                    <a class="btn  btn-default" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="blockquote" data-wysihtml5-display-format-name="false" tabindex="-1" href="javascript:;" unselectable="on">

                                        <span class="glyphicon glyphicon-quote"></span>

                                    </a>
                                </li>
                                <li>
                                    <div class="btn-group">
                                        <a class="btn  btn-default" data-wysihtml5-command="insertUnorderedList" title="Unordered list" tabindex="-1" href="javascript:;" unselectable="on">

                                            <span class="glyphicon glyphicon-list"></span>

                                        </a>
                                        <a class="btn  btn-default" data-wysihtml5-command="insertOrderedList" title="Ordered list" tabindex="-1" href="javascript:;" unselectable="on">

                                            <span class="glyphicon glyphicon-th-list"></span>

                                        </a>
                                        <a class="btn  btn-default" data-wysihtml5-command="Outdent" title="Outdent" tabindex="-1" href="javascript:;" unselectable="on">

                                            <span class="glyphicon glyphicon-indent-right"></span>

                                        </a>
                                        <a class="btn  btn-default" data-wysihtml5-command="Indent" title="Indent" tabindex="-1" href="javascript:;" unselectable="on">

                                            <span class="glyphicon glyphicon-indent-left"></span>

                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <div class="bootstrap-wysihtml5-insert-link-modal modal fade" data-wysihtml5-dialog="createLink">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <a class="close" data-dismiss="modal">×</a>
                                                    <h3>Insert link</h3>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <input value="http://" class="bootstrap-wysihtml5-insert-link-url form-control" data-wysihtml5-dialog-field="href">
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input class="bootstrap-wysihtml5-insert-link-target" checked="" type="checkbox">Open link in new window
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn btn-default" data-dismiss="modal" data-wysihtml5-dialog-action="cancel" href="#">Cancel</a>
                                                    <a href="#" class="btn btn-primary" data-dismiss="modal" data-wysihtml5-dialog-action="save">Insert link</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="btn  btn-default" data-wysihtml5-command="createLink" title="Insert link" tabindex="-1" href="javascript:;" unselectable="on">

                                        <span class="glyphicon glyphicon-share"></span>

                                    </a>
                                </li>
                                <li>
                                    <div class="bootstrap-wysihtml5-insert-image-modal modal fade" data-wysihtml5-dialog="insertImage">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <a class="close" data-dismiss="modal">×</a>
                                                    <h3>Insert image</h3>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <input value="http://" class="bootstrap-wysihtml5-insert-image-url form-control" data-wysihtml5-dialog-field="src">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn btn-default" data-dismiss="modal" data-wysihtml5-dialog-action="cancel" href="#">Cancel</a>
                                                    <a class="btn btn-primary" data-dismiss="modal" data-wysihtml5-dialog-action="save" href="#">Insert image</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="btn  btn-default" data-wysihtml5-command="insertImage" title="Insert image" tabindex="-1" href="javascript:;" unselectable="on">

                                        <span class="glyphicon glyphicon-picture"></span>

                                    </a>
                                </li>
                            </ul><textarea class="textarea" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px; display: none;" placeholder="Message"></textarea><input name="_wysihtml5_mode" value="1" type="hidden"><iframe class="wysihtml5-sandbox" security="restricted" allowtransparency="true" marginwidth="0" marginheight="0" style="display: inline; background-color: rgb(255, 255, 255); border-collapse: separate; border-color: rgb(221, 221, 221); border-style: solid; border-width: 1px; clear: none; float: none; margin: 0px; outline: 0px none rgb(51, 51, 51); outline-offset: 0px; padding: 10px; position: static; top: auto; left: auto; right: auto; bottom: auto; z-index: auto; vertical-align: text-bottom; text-align: start; box-sizing: border-box; box-shadow: none; border-radius: 0px; width: 100%; height: 125px;" height="0" frameborder="0" width="0"></iframe>
                        </div>
                    </form>
                </div>
                <div class="box-footer clearfix">
                    <button type="button" class="pull-right btn btn-default" id="sendEmail">Send
                        <i class="fa fa-arrow-circle-right"></i></button>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-xs-6">

        </div>

    </div>--}}
@stop

@section('customscripts')

    <script>

        jQuery(document).ready(function () {

            /*jQuery.ajax({
             url:'jobs/getopenjobs',
             dataType:'json',
             success: function(data) {

             }
             });*/

        });

    </script>

@stop