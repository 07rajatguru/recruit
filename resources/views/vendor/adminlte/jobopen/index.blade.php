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


    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Job Openings List ({{ $count or 0}})</h2>
            </div>

            <div class="pull-right">
                <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#modal-status" onclick="multipleJobId()">Update Status</button>
                <a class="btn btn-success" href="{{ route('jobopen.create') }}"> Create Job Openings</a>
            </div>

            <div class="pull-right">
                {{--<a class="btn btn-success" href="{{ route('jobopen.create') }}"> Search</a>--}}
               {{-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Search</button>--}}
            </div>
        </div>
    </div>

     <div class="row">
       {{-- <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box-body col-xs-1 col-sm-1 col-md-1" style="width: 7%; float:left; margin: 5px">
                <div style="height:30px;width:70px;background-color:#FFFFFF;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">None ({{ $priority_0 }})
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 15%; float:left; margin: 5px">
                <div style="height:30px;width:150px;background-color:#FF0000;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Urgent Positions ({{ $priority_1 }})
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 13%; float:left; margin: 5px">
                <div style="height:30px;width:130px;background-color:#00B0F0;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">New Positions ({{ $priority_2 }})
                </div>
            </div>

             <div class="box-body col-xs-3 col-sm-3 col-md-3" style="width: 21.5%; float:left; margin: 5px">
                <div style="height:30px;width:220px;background-color:#FABF8F;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Constant Deliveries needed ({{ $priority_3 }})
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 25%; float:left; margin: 5px">
                <div style="height:30px;width:190px;background-color:#92D050;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Identified Candidates ({{ $priority_4 }})
                </div>
            </div>
        </div>--}}
    </div>

    &nbsp;

    <div class="row">
        {{--<div class="col-xs-12 col-sm-12 col-md-12">

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 16%; float:left; margin: 5px">
                <div style="height:30px;width:160px;background-color:yellow;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Revived Positions ({{ $priority_5 }})
                </div>
            </div>

            <div class="box-body col-xs-3 col-sm-3 col-md-3" style="width: 19%; float:left; margin: 5px">
                <div style="height:30px;width:190px;background-color:#808080;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">No Deliveries Needed ({{ $priority_7 }})
                </div>
            </div>

             <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 9.5%; float:left; margin: 5px">
                <div style="height:30px;width:90px;background-color:#B1A0C7;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">On Hold ({{ $priority_8 }})
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2" style="width: 13%; float:left; margin: 5px">
                <div style="height:30px;width:130px;background-color:#92D050;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Closed By Us ({{ $priority_9 }})
                </div>
            </div>

             <div class="box-body col-xs-2 col-sm-2 col-md-2" style="float:left; margin: 5px">
                <div style="height:30px;width:220px;background-color:#FFFFFF;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Closed By Client ({{ $priority_10 }})
                </div>
            </div>
        </div>--}}
    </div>

    &nbsp;

    <div class="row">
        {{--<div class="col-xs-12 col-sm-12 col-md-12">

            <div class="box-body col-xs-2 col-sm-2 col-md-2">
                <div style="height:30px;width:700px;background-color:#808080;font-weight: 600;border-radius: 20px;border: 1px solid black;padding:6px 0px 0px 6px;text-align: center;">Constant Deliveries needed for very old positions where many deliveries are done but no result yet ({{ $priority_6 }})
                </div>
            </div>

        </div>--}}
    </div>
    
    &nbsp;
    
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
            <th>HR/Coordinator  <br/> Name</th>
            <th>Added Date</th>
            <th>No. Of <br/> Positions</th>
            <th>Edu Qualifications</th>
            <th>Target Industries</th>
            <th>Desired Candidate</th>

            {{--<th>Target Date</th>--}}

        </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>

        @foreach($jobList as $key=>$value)
            <tr>
                <td>{{ ++$i }}</td>
                @if(isset($value['access']) && $value['access']==1)
                    <td>{{ Form::checkbox('job_ids',$value['id'],null,array('class'=>'multiple_jobs' ,'id'=>$value['id'] )) }}</td>
                @else
                    <td></td>
                @endif
                <td>
                    <a title="Show"  class="fa fa-circle" href="{{ route('jobopen.show',$value['id']) }}"></a>

                    @if(isset($value['access']) && $value['access']==1)
                        <a title="Edit" class="fa fa-edit" href="{{ route('jobopen.edit',$value['id']) }}"></a>
                    @endif

                    @if(isset($value['access']) && $value['access']==1)
                    @include('adminlte::partials.jobstatus', ['data' => $value, 'name' => 'jobopen','display_name'=>'More Information'])
                    @endif

                    @if($isSuperAdmin)
                    @include('adminlte::partials.jobdelete', ['data' => $value, 'name' => 'jobopen','display_name'=>'Job'])
                    @endif
                    @if(isset($value['access']) && $value['access']==1)
                        <a title="Clone Job"  class="fa fa-clone" href="{{ route('jobopen.clone',$value['id']) }}"></a>
                    @endif
                  

                </td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['am_name'] or '' }}</td>
                <td style="background-color: {{ $value['color'] }}">{{ $value['display_name'] or '' }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['posting_title'] or ''}}</td>
                <td ><a title="Show Associated Candidates" href="{{ route('jobopen.associated_candidates_get',$value['id']) }}">{{ $value['associate_candidate_cnt'] or ''}}</a></td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['location'] or ''}}</td>
                <td>{{ $value['min_ctc'] or ''}}</td>
                <td>{{ $value['max_ctc'] or ''}}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['coordinator_name'] or '' }}</td>
                <td>{{ $value['created_date'] or ''}}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['no_of_positions'] or ''}}</td>
                <td>{{ $value['qual'] or ''}}</td>
                <td>{{ $value['industry'] or ''}}</td>
                <td>{!! $value['desired_candidate'] or ''!!}</td>

                {{--<td>{{ $value['close_date'] or ''}}</td>--}}


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
                <strong>Select Job Priority :</strong> <br>
                {!! Form::select('job_priority', $job_priority,null, array('id'=>'job_priority','class' => 'form-control')) !!}
            </div>

            <input type="hidden" name="job_ids" id="job_ids" value="">

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
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
        $(document).ready(function(){
            var table = jQuery('#jo_table').DataTable( {
                responsive: true,
                "columnDefs": [
                    { "width": "10px", "targets": 0, "order": 'desc' },
                    { "width": "10px", "targets": 1 },
                    { "width": "10px", "targets": 2 },
                    { "width": "10px", "targets": 3 },
                    { "width": "10px", "targets": 4 },
                    { "width": "10px", "targets": 5 },
                    { "width": "10px", "targets": 6 },
                    { "width": "10px", "targets": 7 },
                    { "width": "10px", "targets": 8 },
                    { "width": "10px", "targets": 9 }
                ],
                "pageLength": 100,
                stateSave: true
            });
            new jQuery.fn.dataTable.FixedHeader( table );

            $('#allcb').change(function(){
                if($(this).prop('checked')){
                    $('tbody tr td input[type="checkbox"]').each(function(){
                        $(this).prop('checked', true);
                    });
                }else{
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
                else{
                    $("#allcb").prop('checked', false);
                }
            });

        });

        function multipleJobId(){
            var token = $('input[name="csrf_token"]').val();
            var job_ids = new Array();

            $("input:checkbox[name=job_ids]:checked").each(function(){
                job_ids.push($(this).val());
            });
            alert(job_ids);

            $(".priority").show();
            $("#job_ids").val(job_ids);

            /*$.ajax({
                type : 'POST',
                url : 'jobs/mutijobpriority',
                data : {job_ids : job_ids, '_token':token},
                dataType : 'json',
                success: function(){

                }
            });*/
        }
    </script>
@endsection