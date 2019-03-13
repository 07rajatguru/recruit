@extends('index.headerfooter',['PageName' => 'contact_us'])

@section('body')
<div class="inner_banner">
   <div class="container">
      <h1 class="title text-8 font-weight-extra-bold">Request a Free Demo</h1>
   </div>
</div>
<div class="middle_sec contact_us">
   <div class="container">
      <div class="row">
          <div class="col-lg-10">
             <div class="contact_form">
                <form id="contactForm" class="contactForm" action="{{route('demo.request')}}" method="POST">
                  {{ csrf_field()}}
                   <div class="form-group Border">
                      <input type="text" placeholder="Full Name*" class="form-control" required=""  name="name" id="name">
                   </div>
                   <div class="form-group Border">
                      <input type="email" placeholder="Email Address*" class="form-control" required="" name="email" id="email">
                   </div>
                   <div class="form-group Border">
                      <input type="text" placeholder="Phone*" class="form-control" required="" name="phone" id="phone">
                   </div>
                   <div class="form-group">
                      <input type="text" placeholder="Company*" class="form-control" required="" name="company" id="company">
                   </div>
                   <div class="form-group">
                      <input type="text" placeholder="Preferred date for demo*" class="form-control" required="" name="preferred_date" id="preferred_date">
                   </div>
                   <div class="form-group">
                      <input type="text" placeholder="Preferred time for demo*" class="form-control" required="" name="preferred_time" id="preferred_time">
                   </div>
                   <div class="read-btn text-right">
                      <button class="btn btn-primary btn-modern" title="Submit">submit</button>
                   </div>
                   <div class="clearfix"></div>
                </form>
             </div>
          </div>
       </div>
    </div>
 </div>
@stop