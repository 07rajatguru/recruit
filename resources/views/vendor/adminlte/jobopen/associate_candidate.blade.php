@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <div class="form-group">
                    <strong>Select initial letter:</strong>
                    {{Form::select('letter',$letter_array, $letter, array('id'=>'letter','class'=>'form-control'))}}
                    {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()','id' => 'select_btn']) !!}
                </div>
                <h3>Associate Candidates to Job Opening : {{ $posting_title }}</h3>
                <span> Select Candidates to associate to Job Openings and click on submit button</span>
            </div>

            <div class="pull-right">
                <a class="btn bg-blue" href="/jobs/{{$job_id}}">Back</a>
                <a class="btn btn-info"  onclick="associate_candidates({{ $job_id }});">Submit</a>
            </div>
        </div>
    </div><br/>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="candidate_table">
        <thead>
            <tr>
                <th>{{ Form::checkbox('candidate[]',0 ,null,array('id'=>'allcb')) }}</th>
                <th>Candidate Name</th>
                <th>Candidate Owner</th>
                <th>Candidate Email</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        {{--<tbody>
            @foreach ($candidates as $candidate)
                <tr>
                    <td>{{ Form::checkbox('candidate', $candidate->id,null,array('class'=>'others_cbs' ,'id'=>$candidate->id )) }}</td>
                    <td>{{ $candidate->fname or '' }}</td>
                    <td>{{ $candidate->owner or '' }}</td>
                    <td>{{ $candidate->email or ''}}</td>

                </tr>
            @endforeach
        </tbody>--}}
    </table>
    <input type="hidden" id="token" value="{{ csrf_token() }}">

    <input type="hidden" value="{{ $job_id }}" id="job_id" name="job_id" />

    <div class="row">
        <div class="col-lg-12 margin-tb">

            <div class="pull-right">
                <a class="btn bg-blue" href="/jobs/{{$job_id}}">Back</a>
                <a class="btn btn-info"  onclick="associate_candidates({{ $job_id }});">Submit</a>
            </div>  
        </div>
    </div>
@stop



@section('customscripts')
    <script type="text/javascript">
        $( document ).ready(function()
        {
            $("#letter").select2({"width":"180px"});

            var initial_letter = $("#letter").val();
            var job_id = $("#job_id").val();

            $("#candidate_table").dataTable({
                "bProcessing": true,
                "serverSide": true,
                "columnDefs": [ {orderable: false, targets: [0]} ],
                "ajax":
                {
                    type: "GET",
                    url :"/associate-candidate/all",
                    data: {initial_letter:initial_letter,job_id:job_id},
                    /*beforeSend: function()
                    {
                        document.getElementById("select_btn").value="Loading...";
                    },
                    success: function()
                    {
                        document.getElementById("select_btn").value="Select";
                        console.log("a");
                    }*/
                },
                "pageLength": 100,
                "responsive": true,
                "autoWidth": false,
                "pagingType": "full_numbers",
            });

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

            $('.others_cbs').change(function() {
                if ($(this).prop('checked')) {
                    if ($('.others_cbs:checked').length == $('.others_cbs').length) {
                        $("#allcb").prop('checked', true);
                    }
                }
                else{
                    $("#allcb").prop('checked', false);
                }
            });
        });

        function associate_candidates(jobid){
            var candidate_ids = new Array();
            var token = $("#token").val();
            var app_url = "{!! env('APP_URL'); !!}";

            if(jobid>0){

                $("input:checkbox[name=candidate]:checked").each(function(){
                    candidate_ids.push($(this).val());
                });

                var url = app_url+'/jobs/associate_candidate';

                if(candidate_ids.length > 0){
                    var form = $('<form action="' + url + '" method="post">' +
                            '<input type="hidden" name="_token" value="'+token+'" />' +
                            '<input type="text" name="jobid" value="'+jobid+'" />' +
                            '<input type="text" name="candidate_ids" value="'+candidate_ids+'" />' +
                            '</form>');

                    $('body').append(form);
                    form.submit();
                }
            }
        }

        function select_data()
        {
            $("#candidate_table").dataTable().fnDestroy();
            var initial_letter = $("#letter").val();
            var job_id = $("#job_id").val();

            $("#candidate_table").dataTable(
            {
                "bProcessing": true,
                "serverSide": true,
                "columnDefs": [ {orderable: false, targets: [0]} ],
                "ajax":
                {
                    type: "GET",
                    url :"/associate-candidate/all",
                    data: {initial_letter:initial_letter,job_id:job_id},
                    /*beforeSend: function()
                    {
                        document.getElementById("select_btn").value="Loading...";
                    },
                    success: function()
                    {
                        document.getElementById("select_btn").value="Select";
                        console.log("a");
                    }*/
                },
               
                "pageLength": 100,
                "responsive": true,
                "autoWidth": false,
                "pagingType": "full_numbers",
                "stateSave" : true,
            });
        }
    </script>
@endsection
