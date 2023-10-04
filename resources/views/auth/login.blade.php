@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" >
    
    <link rel="stylesheet" href="{{ asset('fonts/icomoon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
    
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('images/undraw_remotely_2j6y.svg') }}" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6 contents">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4" style="text-align: center;"><br><br><br>
                               <h3 class="mb-4" style="font-size: 25px; font-weight: bold; font-family: Cambria, sans-serif; color: #6c63ff;">Sign In</h3>
                               <h3 class="mb-4">Welcome back !</br>Stay Updated on your Daily Progress.</h3>
                            </div>
                            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                                {!! csrf_field() !!}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                                <div class="form-group first">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" >
                                </div>
                                <div class="form-group last mb-4">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" >
                                </div>
                                <!-- <div class="d-flex mb-5 align-items-center">
                                    <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span class="ml-auto"><a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}" class="forgot-pass">Forgot Password</a></span>
                                </div> -->
                                
                                <button type="submit" class="btn btn-block btn-primary" style="font-size: 18px;">Log In</button>
                                <!-- <span class="d-block text-left my-4 text-muted">&mdash; or login with &mdash;</span>
                                <div class="social-login">
                                    <a href="#" class="facebook">
                                        <span class="icon-facebook mr-3"></span>
                                    </a>
                                    <a href="#" class="twitter">
                                        <span class="icon-twitter mr-3"></span>
                                    </a>
                                    <a href="#" class="google">
                                        <span class="icon-google mr-3"></span>
                                    </a>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>

                <div class="logo-container">
                    <img src="{{ asset('images/black-text-logo.png') }}" class="logo" alt="Easy2hire">
                </div>
            
            </div>
        </div>
    </div>
@stop
@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        
        // Select the input field by its ID and set focus
        $(".first").addClass('field--not-empty');
        $(".last").addClass('field--not-empty');
      

        // Initialize iCheck
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
    </script>
    @yield('js')
@stop
