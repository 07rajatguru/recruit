@extends('adminlte::page')

@section('title', 'All Job Openings')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Job Openings List({{ $count or 0}})</h2>
            </div>
            
            <div class="pull-right">
                @permission('job-create')
                    <a class="btn btn-success" href="{{ route('jobopen.create') }}"> Create Job Openings</a>
                @endpermission
            </div>
        </div>
    </div>
    
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="jo_table">
        <thead>
        <tr>
            <th width="7%">No</th>
            <th width="33%">Position Title</th>
            <th width="10%">Location</th>
            <th width="10%">Associated Candidates Count ( <span id="total_order" style="color: #337ab7;"></span> )</th>
        </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>

        {{-- @foreach($jobList as $key=>$value)
            <tr>
                <td>{{ ++$i }}</td>
               
                <td style="white-space: pre-wrap; word-wrap: break-word;background-color: {{ $value['color'] }};">{{ $value['posting_title'] or ''}}</td>

                <td>{{ $value['city'] }}</td>

                <td ><a title="Show Associated Candidates" href="{{ route('alljobs.associated_candidates_get',$value['id']) }}">{{ $value['associate_candidate_cnt'] or ''}}</a></td>
            </tr>
        @endforeach --}}
        </tbody>
    </table>
@stop

@section('customscripts')

    <script type="text/javascript">
        $(document).ready(function()
        {
            $("#jo_table").DataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0, 'desc'],
                "ajax" : {
                    'url' : 'get-alljobs',
                    'type' : 'get',
                    error: function(){

                    }
                },
                drawCallback:function(settings)
                {
                 $('#total_order').html(settings.json.total);
                },
                responsive : true,
                "pageLength": 50,
                "pagingType" : "full_numbers",
                stateSave : true,

            });

            //$('#jo_table tr:eq(0) th:eq(0)').text("Text");
        });
    </script>
@endsection