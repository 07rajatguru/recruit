@extends('adminlte::page')

@section('title', 'Email Template')

@section('content_header')
    <h1></h1>
@stop

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Email Template Details</h2>
        </div>

        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('emailtemplate.index') }}">Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

            <div class="box-header col-md-6"></div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th scope="row">Name</th>
                        <td>{{ $email_template['name'] }}</td>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <td>{{ $email_template['subject'] }}</td>
                    </tr>
                    <tr>
                        <th>Email Body</th>
                        <td>{!! $email_template['email_body'] !!}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection