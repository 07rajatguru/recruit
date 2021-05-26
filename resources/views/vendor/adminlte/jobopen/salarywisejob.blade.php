@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>
@stop

@section('content')

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
        <div class="col-lg-12 margin-tb">
            @if(isset($financial_year) && $financial_year != '')
                <h4><b>Financial Year</b> : {{ $financial_year }}</h4>
            @endif
            <div class="pull-left">
                <h2>Job Openings List ({{ $count or 0}})</h2>
            </div>

            <div class="pull-right">
                @if(!$isClient)
                    @permission(('update-multiple-jobs-priority'))
                        <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#modal-status" onclick="multipleJobId()">Update Status</button>
                    @endpermission

                    @permission(('job-add'))
                        <a class="btn btn-success" href="{{ route('jobopen.create') }}">Create Job Openings</a>
                    @endpermission
                @endif
                <a class="btn btn-primary" href="{{ route('jobopen.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class = "table-responsive">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="jo_table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                    <th>Action</th>
                    <th>MB</th>
                    <th>Company Name</th>
                    <th>Position Title</th>
                    <th>CA</th>
                    <th>Location</th>
                    <th>Min CTC<br/>(in Lacs)</th>
                    <th>Max CTC<br/>(in Lacs)</th>
                    <th>Added Date</th>
                    <th>No. Of <br/> Positions</th>
                    <th>Edu Qualifications</th>
                    <th>Contact <br/> Point</th>
                    <th>Target Industries</th>
                    <th>Desired Candidate</th>
                </tr>
            </thead>
            <?php $i=0; ?>
            <tbody>
                @foreach($jobList as $key=>$value)
                    <tr>
                        <td>{{ ++$i }}</td>

                        @permission(('update-multiple-jobs-priority'))
                            <td>{{ Form::checkbox('job_ids',$value['id'],null,array('class'=>'multiple_jobs' ,'id'=>$value['id'] )) }}</td>
                        @else
                            <td></td>
                        @endpermission

                        <td>
                            <a title="Show"  class="fa fa-circle" href="{{ route('jobopen.show',$value['id']) }}"></a>

                            @if(isset($value['access']) && $value['access']==1)
                                <a title="Edit" class="fa fa-edit" href="{{ route('jobopen.edit',$value['id']) }}"></a>
                            @endif

                            @if(isset($value['access']) && $value['access']==1)
                                @permission(('change-job-priority'))
                                    @include('adminlte::partials.jobstatus', ['data' => $value, 'name' => 'jobopen','display_name'=>'Job Open'])
                                @endpermission
                            @endif

                            @permission(('job-delete'))
                                @include('adminlte::partials.jobdelete', ['data' => $value, 'name' => 'jobopen','display_name'=>'Job Open','title' => 'Job Open'])
                            @endpermission
                            
                            @if(isset($value['access']) && $value['access']==1)
                                @permission(('clone-job'))
                                    <a title="Clone Job"  class="fa fa-clone" href="{{ route('jobopen.clone',$value['id']) }}"></a>
                                @endpermission
                            @endif
                        </td>
                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['am_name'] or '' }}</td>
                        <td style="background-color: {{ $value['color'] }}">{{ $value['display_name'] or '' }}
                        </td>
                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['posting_title'] or ''}}</td>

                        <td>
                            <a title="Show Associated Candidates" href="{{ route('jobopen.associated_candidates_get',$value['id']) }}">{{ $value['associate_candidate_cnt'] or ''}}</a>
                        </td>

                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['city'] or ''}}</td>
                        <td>{{ $value['min_ctc'] or ''}}</td>
                        <td>{{ $value['max_ctc'] or ''}}</td>
                        <td>{{ $value['created_date'] or ''}}</td>

                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['no_of_positions'] or ''}}</td>
                        <td>{{ $value['qual'] or ''}}</td>
                        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['coordinator_name'] or '' }}</td>
                        <td>{{ $value['industry'] or ''}}</td>
                        <td>{!! $value['desired_candidate'] or ''!!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="modal-status" class="modal text-left fade priority" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title">Select Job Priority</h1>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'jobopen.mutijobpriority']) !!}
                <div class="modal-body">
                    <div class="status">
                        <strong>Select Job Priority :</strong> <br>
                        {!! Form::select('priority', $job_priority,null, array('id'=>'priority','class' => 'form-control')) !!}
                    </div>
                    <div class="error"></div>
                </div>

                <input type="hidden" name="job_ids" id="job_ids" value="">

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $("#job_priority").select2({width:"565px"});
            $("#priority").select2({width:"565px"});

            var table = jQuery('#jo_table').DataTable({

                responsive: true,
                "columnDefs": [
                    { "width": "10px", "targets": 0, "order": 'desc' },
                    { "width": "10px", "targets": 1, "searchable": false, "orderable": false },
                    { "width": "10px", "targets": 2, "searchable": false, "orderable": false },
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "150px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                    { "width": "10px", "targets": 8 },
                    { "width": "10px", "targets": 9 },
                    { "width": "5px", "targets": 10 },
                ],
                "pageLength": 100,
                stateSave: true
            });

            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }

            $('#allcb').change(function() {

                if($(this).prop('checked')) {
                    $('tbody tr td input[type="checkbox"]').each(function(){
                        $(this).prop('checked', true);
                    });
                }
                else {
                    $('tbody tr td input[type="checkbox"]').each(function(){
                        $(this).prop('checked', false);
                    });
                }
            });
            $('.multiple_jobs').change(function() {
                if ($(this).prop('checked')) {
                    if ($('.multiple_jobs:checked').length == $('.multiple_jobs').length) {
                        $("#allcb").prop('checked', true);
                    }
                }
                else {
                    $("#allcb").prop('checked', false);
                }
            });
        });

        function multipleJobId() {

            var token = $('input[name="csrf_token"]').val();
            var app_url = "{!! env('APP_URL'); !!}";
            var job_ids = new Array();

            $("input:checkbox[name=job_ids]:checked").each(function(){
                job_ids.push($(this).val());
            });

            $("#job_ids").val(job_ids);

            $.ajax({

                type : 'POST',
                url : app_url+'/jobs/checkJobId',
                data : {job_ids : job_ids, '_token':token},
                dataType : 'json',

                success: function(msg) {

                    $(".priority").show();

                    if (msg.success == 'success') {

                        $(".status").show();
                        $(".error").empty();
                        $('#submit').show();
                    }
                    else {

                        $(".status").hide();
                        $(".error").empty();
                        $('#submit').hide();
                        $(".error").append(msg.err);
                    }
                }
            });
        }
    </script>
@endsection