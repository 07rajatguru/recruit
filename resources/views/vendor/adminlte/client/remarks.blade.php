@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')
<div class="col-md-9"> 
    <div>
     	 @include('adminlte::client.remarksnew',array('client_id' => $client_id,'user_id'=>$user_id))    	 
    </div>

    <div>
    	@include('adminlte::client.remarkslist',array('post' => $post))
    </div>
</div>
@stop

