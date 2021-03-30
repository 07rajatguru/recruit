@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

@extends('adminlte::page')

@section('title', 'Applicant Candidates')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb" style="margin-top:15px;">
            <div class="pull-left">
                <h3>Applicant Candidates of Job Opening : {{ $posting_title }}</h3>
                <span></span>
            </div>
        </div>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th width="2%">No</th>
            <th>Action</th>
            <th>Applicant<br/>Date</th>
            <th>Candidate<br/>Name</th>
            <th>Candidate<br/>Email</th>
            <th width="10%">Mobile<br/>Number</th>
            <th>Functional<br/>Roles</th>
            <th>Last<br/>Employer</th>
            <th>Last<br/>Job Title</th>
            <th width="7%">Current<br/>Salary</th>
            <th width="7%">Expected<br/>Salary</th>
        </tr>
        <?php $i = 0; ?>

        @foreach ($candidates as $candidate)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a data-toggle="modal" href="#modal-candidate" class="fa fa-star-o" title="Associate Candidate"></a>
                </td>
                <td>{{ $candidate->applicant_date }}</td>
                <td><a target="_blank" title="Show Candidate" href="{{ route('candidate.show',$candidate->id) }}">{{ $candidate->full_name }}</a></td>
                <td>{{ $candidate->email }}</td>
                <td>{{ $candidate->mobile }}</td>
                <td>{{ $candidate->functional_roles_name }}</td>
                <td>{{ $candidate->current_employer }}</td>
                <td>{{ $candidate->current_job_title }}</td>
                <td>{{ $candidate->current_salary }}</td>
                <td>{{ $candidate->expected_salary }}</td>
            </tr>
        @endforeach
    </table>

    <input type="hidden" name="token" id="token" value="{{ csrf_token() }}">

    <div id="modal-candidate" class="modal text-left fade">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title">Associate Candidate</h1>
                </div>

                {!! Form::open(['method' => 'POST', 'route' => ["jobopen.associate_candidate"]]) !!}
                
                <div class="modal-body">
                    Are you sure wan't to associate this candidate?
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
                {!! Form::close() !!}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>
@endsection