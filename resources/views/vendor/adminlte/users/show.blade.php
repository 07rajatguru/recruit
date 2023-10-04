@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Users Details</h2>
            </div>
           <div class="pull-right">
                @include('adminlte::users.UserOldData', ['old_data' => $user_old_data, 'display_name'=>'User Old Data'])
                <a class="btn btn-primary" href="{{ route('users.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name : </th>
                            <td>{{ $user->name }}</td>
                            <th>Email :</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Department :</th>
                            <td>{{ $user->department }}</td>
                            <th>Role :</th>
                            <td>
                                <label class="label label-success">{{ $user->display_name }}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>Working Hours :</th>
                            <td>{{ $user->working_hours }}</td>
                            <th>Half Day Working Hours :</th>
                            <td>{{ $user->half_day_working_hours }}</td>
                        </tr>

                        @if(isset($user->employment_type) && $user->employment_type == 'Intern')
                            <tr>
                                <th>Employment Type :</th>
                                <td>{{ $user->employment_type }} - for {{ $user->intern_month }} Months</td>
                                <th>Status :</th>
                                <td>{{ $user->status }}</td>
                            </tr>
                        @else
                            <tr>
                                <th>Employment Type :</th>
                                <td>{{ $user->employment_type }}</td>
                                <th>Status :</th>
                                <td>{{ $user->status }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            
            jQuery('#user_old_data_table').DataTable({
                responsive: true,
            });
        });
    </script>
@endsection