@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h3>Associate Candidates to Job Opening : {{ $posting_title }}</h3>
                <span> Select Candidates to associate to Job Openings and click on submit button</span>
            </div>

            <div class="pull-right">
                <a class="btn bg-blue" href="/jobs/{{$job_id}}">Back</a>
                <a class="btn btn-info"  onclick="associate_candidates({{ $job_id }});">Submit</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table class="table table-bordered">
        <tr>
            <th>{{ Form::checkbox('candidate[]',0 ,null,array('id'=>'allcb')) }}</th>
            <th>Candidate Name</th>
            <th>Candidate Owner</th>
            <th>Candidate Email</th>
        </tr>
        <?php $i=0; ?>
        @foreach ($candidates as $candidate)
            <tr>
                <td>{{ Form::checkbox('candidate', $candidate->id,null,array('class'=>'others_cbs' ,'id'=>$candidate->id )) }}</td>
                <td>{{ $candidate->fname or '' }}</td>
                <td>{{ $candidate->owner or '' }}</td>
                <td>{{ $candidate->email or ''}}</td>

            </tr>
        @endforeach

    </table>
    <input type="hidden" id="token" value="{{ csrf_token() }}">

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
        $( document ).ready(function() {
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
    </script>
@endsection
