@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    <link rel="stylesheet" href="/css/style.css" >
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')

    <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 80px;">
        <!-- <div class="box-body col-xs-6 col-sm-6 col-md-6" style="margin-top: 50px;">
            <div class="col-md-12">
                <h3>About Easy2hire</h3>
                <p>Easy2Hire is a recruitment solution for easing your hiring woes. Easy2Hire is conceptualized to streamline operations for a recruitment agency. The solution bundles the capabilities of a CRM, tracking solution to improve collaboration, transparency and control over various business aspects.</p>

                <p>We have been studying the recruitment industry for a while now and believe technology is the only way to improve how things work in the recruitment ecosystem. Easy2Hire provides you with all the tools to manage, streamline and simplify your business operations- no matter the scale.</p>

                <img src="/images/overview.jpg" style="height: 250px;width: 550px;" alt="Easy2hire">
            </div>
        </div> -->
        <!-- <div class="box-body col-xs-6 col-sm-6 col-md-6" style="margin-top: 80px;"> -->
            <div class="login-box" style="box-shadow: 0 0 4px 4Px #888888;">
                <!-- <div class="login-logo">
                    <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
                </div> -->
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <!-- <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p> -->
                    <div class="logo text-center">
                        <img src="/images/black-text-logo.png" style="width:150px;height:40px;" alt="Easy2hire">
                    </div><br>
                    <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                        {!! csrf_field() !!}

                        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                   placeholder="{{ trans('adminlte::adminlte.email') }}">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input type="password" name="password" class="form-control"
                                   placeholder="{{ trans('adminlte::adminlte.password') }}">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="row">
                            {{--<div class="col-xs-8">
                                <div class="checkbox icheck">
                                    <label>
                                        <input type="checkbox" name="remember"> {{ trans('adminlte::adminlte.remember_me') }}
                                    </label>
                                </div>
                            </div>--}}
                            <!-- /.col -->
                            <div class="col-xs-4" style="margin-left: 110px;">
                                <button type="submit" class="btn btn-primary btn-block btn-flat" style="background-color: #00aace;">{{ trans('adminlte::adminlte.sign_in') }}</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    {{--<div class="auth-links">
                        <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}"
                           class="text-center"
                        >{{ trans('adminlte::adminlte.i_forgot_my_password') }}</a>
                        <br>

                    </div>--}}
                </div><!-- /.login-box-body -->
            </div><!-- /.login-box -->
        </div>
    </div>
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    @yield('js')
@stop
