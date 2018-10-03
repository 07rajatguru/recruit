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
            <th>Action</th>
            <th>MB</th>
            <th>Company Name</th>
            <th>Position Title</th>
            <th>CA</th>
            <th>Location</th>
            <th>Min CTC<br/>(in Lacs)</th>
            <th>Max CTC<br/>(in Lacs)</th>
            <th>HR/Coordinator Name</th>
            <th>No. Of Positions</th>
            <th>Edu Qualifications</th>
            <th>Target Industries</th>
            <th>Desired Candidate</th>
            <th>Added Date</th>
            {{--<th>Target Date</th>--}}

        </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>

        @foreach($jobList as $key=>$value)
            <tr>
                <td>{{ ++$i }}</td>
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
                <td >{{ $value['coordinator_name'] or '' }}</td>
                <td>{{ $value['no_of_positions'] or ''}}</td>
                <td>{{ $value['qual'] or ''}}</td>
                <td>{{ $value['industry'] or ''}}</td>
                <td>{{ $value['desired_candidate'] or ''}}</td>
                <td>{{ $value['created_date'] or ''}}</td>
                {{--<td>{{ $value['close_date'] or ''}}</td>--}}


            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
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
                    { "width": "10px", "targets": 7 }
                ],
                "pageLength": 100,
                stateSave: true
            });
            new jQuery.fn.dataTable.FixedHeader( table );

            
        });
    </script>
@endsection