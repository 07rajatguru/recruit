@extends('index.headerfooter',['PageName' => 'contact_us'])

@section('body')
<div class="inner_banner">
   <div class="container">
      <h1 class="title text-8 font-weight-extra-bold">Contact Us</h1>
   </div>
</div>
<div class="middle_sec contact_us">
   <div class="container">
      <div class="row">
         <div class="col-lg-2 order-lg-12">
            <div class="contact_location">
               <h2 class="title font-weight-normal text-7"><strong class="font-weight-extra-bold">Contact</strong> Information</h2>
               <ul class="info">
                   <li><i class="fas fa-map-marker-alt"></i>
                     H-703, 7th Floor, TITANIUM CITY CENTER-Business Park, Near IOC Petrol pump, Prahaladnagar, Satellite, Ahmedabad 380015 Gujarat, India                         
                   </li>
                   <li><i class="fas fa-phone"></i>
                     <a href="tel:+918487050452" title="Call Us On: +91 8487050452">
                        +91 8487050452</a>
                   </li>
                   <li><i class="far fa-clock"></i>
                    Monday-Saturday (10AM-7PM)
                   </li>
                   <li><i class="far fa-envelope"></i>
                    <a href="mailto:info@trajinfotech.com" title="Email Us On: info@trajinfotech.com">
                       info@trajinfotech.com</a>
                   </li>
                   <li><i class="fas fa-globe-asia"></i>
                    <a href="http://trajinfotech.com/" title="www.trajinfotech.com" target="_blank">
                      www.trajinfotech.com</a>
                   </li>
                </ul>
                <ul class="social">
                   <li><a href="#" title="Follow Us On: Facebook"><i class="fab fa-facebook-f"></i></a></li>
                   <li><a href="#" title="Follow Us On: Twitter"><i class="fab fa-twitter"></i></a></li>
                   <li><a href="#" title="Follow Us On: Linkedin"><i class="fab fa-linkedin-in"></i></a></li>
                </ul>
             </div>
          </div>
          <div class="col-lg-10">
             <div class="contact_form">
                <form id="contactForm" class="contactForm" action="{{route('contact.us')}}" method="POST">
                  {{ csrf_field()}}
                   <h2 class="title font-weight-bold">ARE YOU READY TO BE MORE PRODUCTIVE AS A RECRUITMENT AGENCY? 
                      <span class="sub font-weight-normal">Get in touch with our experts for an insightful discussion. You can also share your feedback, suggestions and questions by filling the form below. We will get back to you within 24 hours.</span>
                   </h2>
                   <div class="form-group Border">
                      <input type="text" placeholder="Full Name*" class="form-control" required=""  name="name" id="name">
                      <label class="error" style="display: none;">Please enter your Full Name.</label>
                   </div>
                   <div class="form-group Border">
                      <input type="email" placeholder="Email Address*" class="form-control" required="" name="email" id="email">
                   </div>
                   <div class="form-group Border">
                      <input type="text" placeholder="Subject*" class="form-control" required="" name="subject" id="subject">
                   </div>
                   <div class="form-group">
                      <textarea placeholder="Message*" class="form-control textarea" required="" name="message" id="message"></textarea>
                   </div>
                   <div class="form-group">
                      <input type="hidden" class="form-control" name="check" id="check" value="hello">
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
    <div class="map">
       <iframe src="https://maps.google.com/maps?width=100%&amp;height=400&amp;hl=en&amp;q=Titanium%20City%20Center%2CAhmedabad+(Your%20Business%20Name)&amp;ie=UTF8&amp;t=&amp;z=14&amp;iwloc=B&amp;output=embed" ></iframe>
    </div>
 </div>
@stop