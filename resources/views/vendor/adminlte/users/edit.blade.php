@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Edit New User</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>

            </div>

        </div>

    </div>

    @if (count($errors) > 0)

        <div class="alert alert-danger">

            <strong>Whoops!</strong> There were some problems with your input.<br><br>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Name:</strong>

                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control','tabindex' => '1')) !!}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Email:</strong>

                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control','tabindex' => '2')) !!}

            </div>

        </div>

         <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Secondary Gmail:</strong>

                {!! Form::text('semail', $semail, array('placeholder' => 'Secondary Email','class' => 'form-control','tabindex' => '3')) !!}

            </div>

        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Password:</strong>

                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control','tabindex' => '4')) !!}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Confirm Password:</strong>

                {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control','tabindex' => '5')) !!}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Reports To :</strong>

                {!! Form::select('reports_to', $reports_to,isset($userReportsTo) ? $userReportsTo : null, array('id'=>'reports_to','class' => 'form-control','tabindex' => '6')) !!}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Floor Incharge :</strong>

                {!! Form::select('floor_incharge', $floor_incharge,isset($userFloorIncharge) ? $userFloorIncharge : 0, array('id'=>'floor_incharge','class' => 'form-control','tabindex' => '7')) !!}

            </div>

        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Select Company :</strong>

                {!! Form::select('company_id', $companies,isset($user->compnay_id) ? $user->compnay_id : null, array('id'=>'company_id','class' => 'form-control','tabindex' => '8')) !!}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Role:</strong>

                {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','tabindex' => '9')) !!}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Type:</strong>

                {!! Form::select('type', $type, null, array('class' => 'form-control','tabindex' => '10')) !!}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong> Generate Report : </strong> &nbsp;&nbsp;
                {!! Form::radio('daily_report','Yes', true) !!}
                {!! Form::label('Yes') !!} &nbsp;&nbsp;
                {!! Form::radio('daily_report','No') !!}
                {!! Form::label('No') !!}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Status:</strong>&nbsp;&nbsp;
                {!! Form::radio('status','Active') !!}
                {!! Form::label('Active') !!} &nbsp;&nbsp;
                {!! Form::radio('status','Inactive') !!}
                {!! Form::label('Inactive') !!}

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Account Manager:</strong>&nbsp;&nbsp;
                {!! Form::radio('account_manager','Yes') !!}
                {!! Form::label('Yes') !!} &nbsp;&nbsp;
                {!! Form::radio('account_manager','No') !!}
                {!! Form::label('No') !!}

            </div>

        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 text-center">

            <button type="submit" class="btn btn-primary">Submit</button>

        </div>

    </div>

    {!! Form::close() !!}

@endsection