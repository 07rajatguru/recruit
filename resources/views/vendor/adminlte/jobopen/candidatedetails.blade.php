@extends('adminlte::page')

@section('title', 'Job Openings')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h3>Associated Candidates</h3>
                <span> </span>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{url()->previous()}}"> Back</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table class="table table-bordered" id="candidate_details">
        <tr>
            <th style="width: 50%;">Candidate Name</th>
            <th>Action</th>
        </tr>
        <?php $i = 0; ?>
        @foreach ($candidate_details as $key => $value)
        	<tr>
        		<td>{{ $value->fname }}</td>
        		<td><a target="_blank" href="../../{{ $value->file }}"><i  class="fa fa-fw fa-download"></i></a></td>
        	</tr>
        @endforeach
    </table>
@stop