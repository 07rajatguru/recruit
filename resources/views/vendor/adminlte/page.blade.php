@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                                {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                            </a>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    @else
                        <!-- Logo -->
                            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                                <!-- mini logo for sidebar mini 50x50 pixels -->
                                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                                <!-- logo for regular state and mobile devices -->
                                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
                            </a>

                            <!-- Header Navbar -->
                            <nav class="navbar navbar-static-top" role="navigation">
                                <!-- Sidebar toggle button-->
                                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                                </a>
                            @endif
                            <!-- Navbar Right Menu -->
                                <div class="navbar-custom-menu">

                                    <ul class="nav navbar-nav">
                                        <li class="dropdown messages-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-plus"></i>
                                                ADD
                                            </a>
                                            <ul class="dropdown-menu add-button-home">
                                                <li>
                                                    <!-- inner menu: contains the actual data -->
                                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
                                                        <ul class="menu" style=" width: 100%; height;150px">
                                                            <li>
                                                                <a href="/client/create">
                                                                    Add Client
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="/jobs/create">
                                                                    Add Job Openings
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="/candidate/create">
                                                                    Add Candidate
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="/interview/create">
                                                                    Add Interview
                                                                </a>
                                                            </li>
                                                           {{-- <li>
                                                                <a href="/bnm/create">
                                                                    Add Bills
                                                                </a>
                                                            </li>--}}
                                                            <li>
                                                                <a href="/todos/create">
                                                                    Add Todos
                                                                </a>
                                                            </li>
                                                        </ul><div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 131.14754098360655px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div></div>
                                                </li>
                                            </ul>
                                        </li>


                                        <li class="dropdown messages-menu">
                                            <a href="javascript:void(0);" onclick="readAllNotifications();" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-envelope-o"></i>
                                                <span class="label label-success notification-number">4</span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="header notification-display">You have 0 messages</li>
                                                <li>
                                                    <!-- inner menu: contains the actual data -->
                                                    <div class="slimScrollDiv " style="position: relative; overflow: hidden; width: auto; height: 200px;">
                                                        <ul class="menu notification-ul" style="overflow: hidden; width: 100%; height: 200px;">
                                                        </ul>
                                                        <div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 131.14754098360655px; background-position: initial initial; background-repeat: initial initial;"></div>
                                                        <div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div></div>
                                                </li>
                                                <li class="footer"><a href="/notifications">See All Messages</a></li>
                                            </ul>
                                        </li>

                                        <li class="dropdown messages-menu">
                                            <a href="javascript:void(0);" onclick="readAllTodos();" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-list"></i>
                                                <span class="label label-success todos-number">0</span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="header todos-display">You have 0 Todo's</li>
                                                <li>
                                                    <!-- inner menu: contains the actual data -->
                                                    <div class="slimScrollDiv " style="position: relative; overflow: hidden; width: auto; height: 200px;">
                                                        <ul class="menu todos-ul" style="overflow: hidden; width: 100%; height: 200px;">
                                                        </ul>
                                                        <div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 131.14754098360655px; background-position: initial initial; background-repeat: initial initial;"></div>
                                                        <div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div></div>
                                                </li>
                                                <li class="footer"><a href="/todos">See All Todo's</a></li>
                                            </ul>
                                        </li>

                                        <li class="dropdown messages-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                @if(\Auth::user()->id)
                                                    {{ \Auth::user()->name }}
                                                @endif
                                                <i class="fa fa-arrow-down"></i>
                                            </a>
                                            <ul class="dropdown-menu add-button-home">
                                                <li>
                                                    <!-- inner menu: contains the actual data -->
                                                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; /*height: 200px;*/">
                                                        <ul class="menu" style="overflow: hidden; width: 100%;/* height: 200px;*/">
                                                            <li>
                                                                @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                                                    <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                                                        <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                                                    </a>
                                                                @else
                                                                    <a href="#"
                                                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                                    >
                                                                        <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                                                    </a>
                                                                    <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                                                        @if(config('adminlte.logout_method'))
                                                                            {{ method_field(config('adminlte.logout_method')) }}
                                                                        @endif
                                                                        {{ csrf_field() }}
                                                                    </form>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                        <div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 131.14754098360655px; background-position: initial initial; background-repeat: initial initial;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div></div>
                                                </li>
                                            </ul>
                                        </li>


                                    </ul>
                                </div>
                            @if(config('adminlte.layout') == 'top-nav')
                    </div>
                    @endif
                </nav>
        </header>

    @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu">
                        @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>
    @endif

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
                <div class="container">
                @endif

                <!-- Content Header (Page header) -->
                    <section class="content-header">
                        @yield('content_header')
                    </section>

                    <!-- Main content -->
                    <section class="content">

                        @yield('content')

                    </section>
                    <!-- /.content -->
                    @if(config('adminlte.layout') == 'top-nav')
                </div>
                <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/app.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
    <script src="{{ asset('js/multiselect.min.js') }}"></script>
    <script>
        jQuery(document).ready(function () {
            getNotifications();
            var interval = 1000 * 60 * 1;
            setInterval(function(){getNotifications();},interval)

            getTodos();
        });

        function getNotifications(){
            jQuery.ajax({
                url:'/notifications/all',
                dataType:'json',
                success: function(data){
                    console.log(data);
                    $(".notification-ul").empty();
                    for (var i=0; i<data.length; i++){
                        var li = '';
                        li += '<li class="notification-li">';
                            li += '<a href="'+data[i].link+'" target="_blank">';
                                li += '<h4>';
                                    li += data[i].module;
                                li += '</h4>';
                                li += '<p>'+data[i].msg+'</p>';
                            li += '</a>';
                        li += '</li>';
                        $(".notification-ul").append(li);
                       // $('#notification').append($('<li></li>').html(data[i].message));
                    }
                    $(".notification-number").html(data.length);
                    $(".notification-display").html("You have "+data.length+" new notifications");
                }
            });
        }
        
        function readAllNotifications() {
            jQuery.ajax({
                url:'/notifications/read',
                dataType:'json',
                success: function(data){
                    $(".notification-number").html(0);
                }
            });
        }

        function getTodos(){
            jQuery.ajax({
                url:'/todos/all',
                dataType:'json',
                success: function(data){
                    console.log(data);
                    $(".todos-ul").empty();
                    for (var i=0; i<data.length; i++){
                        var li = '';
                        li += '<li class="todos-li">';
                            li += '<a href="#">';
                                li += '<h4 style="margin-left:5px;cursor: auto;">';
                                    li += data[i].subject;
                                li += '</h4>';
                                //li += '<p>'+data[i].subject+'</p>';
                            li += '</a>';
                        li += '</li>';
                        $(".todos-ul").append(li);
                       // $('#notification').append($('<li></li>').html(data[i].message));
                    }
                    $(".todos-number").html(data.length);
                    $(".todos-display").html("You have "+data.length+" new todos");
                }
            });
        }

        function readAllTodos() {
            jQuery.ajax({
                url:'/todos/read',
                dataType:'json',
                success: function(data){
                    $(".todos-number").html(0);
                }
            });
        }
    </script>

    @stack('js')
    @yield('js')
@stop
