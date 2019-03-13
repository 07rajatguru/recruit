@extends('index.headerfooter',['PageName' => 'index.php'])

@section('body')
<div role="main" class="main">
  <div class="slider-container rev_slider_wrapper" style="height: 100vh;">
    <div id="revolutionSlider" class="slider rev_slider" data-version="5.4.8" data-plugin-revolution-slider data-plugin-options="{'sliderLayout': 'fullscreen', 'delay': 9000, 'gridwidth': 1140, 'gridheight': 800, 'responsiveLevels': [4096,1200,992,500]}">
      <ul>
        <li class="slide-overlay" data-transition="fade">
          <img src="easy2hire/img/Slider-1.jpg"  
          alt=""
          data-bgposition="center center" 
          data-bgfit="cover" 
          data-bgrepeat="no-repeat" 
          class="rev-slidebg">
          <h3 class="tp-caption font-weight-bold text-color-light ml-2"
          data-fontsize="['30', '30', '30', '40']" 
          data-lineheight="['32', '32', '32', '45']" 
          data-width="['800', '800', '600', '500']"
          data-textAlign="['inherit', 'inherit', 'left', 'left']" 
          data-whitespace="['normal', 'normal', 'nowrap', 'normal']"
          data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:1.5;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
          data-x="left"
          data-y="['center','center','center','top']" data-voffset="['-30','-30','-30','30']" data-lineheight="['26','26','26','45']">World’s Smartest Digital Solution Designed for Recruitment Agencies
        </h3>
        <div class="tp-caption font-weight-normal text-color-light ws-normal ml-2"
        data-frames='[{"from":"opacity:0;","speed":300,"to":"o:1;","delay":2000,"split":"chars","splitdelay":0.03,"ease":"Power2.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'
        data-x="left"
        data-y="['center','center','center','top']" data-voffset="['53','53','53','255']"
        data-width="['530','530','530','500']"
        data-fontsize="['18','18','18','40']"
        data-lineheight="['26','26','26','45']"
        style="color: #b5b5b5;">Easy2Hire modernizes and automates workflow,helping you increase operational efficiency & productivity up to 33%.</div>

        <a class="tp-caption btn btn-primary btn-rounded font-weight-semibold ml-2"
        data-frames='[{"delay":2500,"speed":2000,"frame":"0","from":"opacity:0;y:50%;","to":"o:1;y:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
        data-hash
        data-hash-offset="85"
        href="/demo_request"
        data-x="left" data-hoffset="0"
        data-y="['center','center','center','top']" data-voffset="['133','133','133','655']"
        data-whitespace="nowrap"  
        data-fontsize="['14','14','14','33']" 
        data-paddingtop="['15','15','15','40']"
        data-paddingright="['45','45','45','110']"
        data-paddingbottom="['15','15','15','40']"         
        data-paddingleft="['45','45','45','110']">Consult our Experts Now </a>
      </li>
      <li class="slide-overlay" data-transition="fade">
        <img src="easy2hire/img/Slider-2.jpg"  
        alt=""
        data-bgposition="center center" 
        data-bgfit="cover" 
        data-bgrepeat="no-repeat" 
        class="rev-slidebg">

        <h3 class="tp-caption font-weight-bold text-color-light ml-2"
        data-fontsize="['24', '24', '22', '40']" 
        data-lineheight="['32', '32', '32', '45']" 
        data-width="['800', '800', '600', '500']"
        data-textAlign="['inherit', 'inherit', 'left', 'left']" 
        data-whitespace="['normal', 'normal', 'nowrap', 'normal']"
        data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:1.5;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
        data-x="left"
        data-y="['center','center','center','top']" data-voffset="['-30','-30','-30','-30']" data-lineheight="['26','26','26','45']">Grow Revenues from Your Recruitment Buss,Exponentially!</h3>
        <div class="tp-caption font-weight-normal text-color-light ws-normal ml-2"
        data-frames='[{"from":"opacity:0;","speed":300,"to":"o:1;","delay":2000,"split":"chars","splitdelay":0.03,"ease":"Power2.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'
        data-x="left"
        data-y="['center','center','center','top']" data-voffset="['53','53','53','255']"
        data-width="['530','530','530','500']"
        data-fontsize="['18','18','18','40']"
        data-lineheight="['26','26','26','45']"
        style="color: #b5b5b5;">Grow Revenues from Your Recruitment Business, Exponentially!Easy2Hire helps you stay vigilant so that you actively track leads, candidates, tasks and clients. This means you will never miss on a growth opportunity again.</div>

        <a class="tp-caption btn btn-primary btn-rounded font-weight-semibold ml-2"
        data-frames='[{"delay":2500,"speed":2000,"frame":"0","from":"opacity:0;y:50%;","to":"o:1;y:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
        data-hash
        data-hash-offset="85"
        href="/features"
        data-x="left" data-hoffset="0"
        data-y="['center','center','center','top']" data-voffset="['133','133','133','665']"
        data-whitespace="nowrap"
        data-fontsize="['14','14','14','33']" 
        data-paddingtop="['15','15','15','40']"
        data-paddingright="['45','45','45','110']"
        data-paddingbottom="['15','15','15','40']"         
        data-paddingleft="['45','45','45','110']">Discover Amazing Features</a>

      </li>
      <li class="slide-overlay" data-transition="fade">
        <img src="easy2hire/img/Slider-3.jpg"  
        alt=""
        data-bgposition="center center" 
        data-bgfit="cover" 
        data-bgrepeat="no-repeat" 
        class="rev-slidebg">

        <h3 class="tp-caption font-weight-bold text-color-light ml-2"
        data-fontsize="['24', '24', '22', '40']" 
        data-lineheight="['32', '32', '32', '45']" 
        data-width="['800', '800', '600', '500']"
        data-textAlign="['inherit', 'inherit', 'left', 'left']"
        data-whitespace="['normal', 'normal', 'nowrap', 'normal']"
        data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:1.5;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
        data-x="left"
        data-y="['center','center','center','top']" data-voffset="['-30','-30','-30','-30']" data-lineheight="['26','26','26','45']">Make Better Business Decisions with Densights on an Intuitive Dashboard</h3>
        <div class="tp-caption font-weight-normal text-color-light ws-normal text-left ml-2"
        data-frames='[{"from":"opacity:0;","speed":300,"to":"o:1;","delay":2000,"split":"chars","splitdelay":0.03,"ease":"Power2.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'
        data-x="left"
        data-y="['center','center','center','top']" data-voffset="['53','53','53','255']"
        data-width="['530','530','530','500']"
        data-fontsize="['18','18','18','40']"
        data-lineheight="['26','26','26','45']"
        style="color: #b5b5b5;">Track daily/monthly activity- clients added, today’s/tomorrow’s interviews & more from a single screen. Manage operations easily and get better at taking important decisions.</div>

        <a class="tp-caption btn btn-primary btn-rounded font-weight-semibold ml-2"
        data-frames='[{"delay":2500,"speed":2000,"frame":"0","from":"opacity:0;y:50%;","to":"o:1;y:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
        data-hash
        data-hash-offset="85"
        href="/front-dashboard"
        data-x="left" data-hoffset="0"
        data-y="['center','center','center','top']" data-voffset="['133','133','133','655']"
        data-whitespace="nowrap"  
        data-fontsize="['14','14','14','33']" 
        data-paddingtop="['15','15','15','40']"
        data-paddingright="['45','45','45','110']"
        data-paddingbottom="['15','15','15','40']"         
        data-paddingleft="['45','45','45','110']">Explore Dashboard Feature</a>

      </li>
      <li class="slide-overlay" data-transition="fade">
        <img src="easy2hire/img/Slider-4.jpg"  
        alt=""
        data-bgposition="center center" 
        data-bgfit="cover" 
        data-bgrepeat="no-repeat" 
        class="rev-slidebg">

        <h3 class="tp-caption font-weight-bold text-color-light ml-2"
        data-textAlign="['inherit', 'inherit', 'left', 'left']"
        data-fontsize="['24', '24', '22', '40']" 
        data-lineheight="['32', '32', '32', '45']" 
        data-width="['800', '800', '600', '500']" 
        data-whitespace="['normal', 'normal', 'nowrap', 'normal']"
        data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:1.5;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
        data-x="left"
        data-y="['center','center','center','top']" data-voffset="['-30','-30','-30','-30']" data-lineheight="['26','26','26','45']">Easy2Hire is the Difference Between a Successful & Struggling Recruitment Agency</h3>
        <div class="tp-caption font-weight-normal text-color-light ws-normal text-left ml-2"
        data-frames='[{"from":"opacity:0;","speed":300,"to":"o:1;","delay":2000,"split":"chars","splitdelay":0.03,"ease":"Power2.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'
        data-x="left"
        data-y="['center','center','center','top']" data-voffset="['53','53','53','255']"
        data-width="['530','530','530','500']"
        data-fontsize="['18','18','18','40']"
        data-lineheight="['26','26','26','45']"
        style="color: #b5b5b5;">Instead of being just a digital solution, Easy2Hire is the ultimate weapon in your business development and management armory.</div>

        <a class="tp-caption btn btn-primary btn-rounded font-weight-semibold ml-2"
        data-frames='[{"delay":2500,"speed":2000,"frame":"0","from":"opacity:0;y:50%;","to":"o:1;y:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
        data-hash
        data-hash-offset="85"
        href="/overview"
        data-x="left" data-hoffset="0"
        data-y="['center','center','center','top']" data-voffset="['133','133','133','655']"
        data-whitespace="nowrap"  
        data-fontsize="['14','14','14','33']" 
        data-paddingtop="['15','15','15','40']"
        data-paddingright="['45','45','45','110']"
        data-paddingbottom="['15','15','15','40']"         
        data-paddingleft="['45','45','45','110']">Realize the Power</a>
      </li>
    </ul>
    </div>
  </div>

  <section class="section section-height-3 section-parallax bg-color-grey-scale-1 border-0 m-0 appear-animation" data-appear-animation="fadeIn" data-plugin-parallax data-plugin-options="{'speed': 1.5, 'parallaxHeight': '100%', 'offset': 70}" data-image-src="easy2hire/img/parallax/parallax-corporate-14-3.jpg">
    <div class="container container-lg">
      <div class="row justify-content-between align-items-center">
        <div class="col-md-7 order-2 order-md-1 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="200">
          <span class="font-weight-bold text-color-dark opacity-8 text-4">Easy2Hire saves you ample amount of time by automating several daily tasks your team needs to complete on a daily basis.</span>
          <h2 class="font-weight-bold text-9 mb-4">Save Hundreds of Hours Every Month with Automated Workflow</h2>
          <ul class="list list-icons pb-2 mb-4">  
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Consolidate interview schedules.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Automate client responses & job openings.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Schedule important tasks.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Email templates for client & candidate-side emailing.</span></li>
          </ul>
          <a href="/time_saver" class="btn btn-primary font-weight-semibold rounded-0 btn-px-5 py-3 text-2">LEARN MORE</a>
        </div>
        <div class="col-md-4 text-center text-md-left order-1 order-md-2 mb-5 mb-md-0 mr-lg-5 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="400">
          <img src="easy2hire/img/1_home.png" alt="" class="img-fluid" style="height:330px;max-width:530px;margin-left:-120px;" />
        </div>
      </div>
    </div>
  </section>

  <section class="section section-height-3 section-parallax bg-color-light border-0 m-0" data-plugin-parallax data-plugin-options="{'speed': 1.5, 'parallaxHeight': '100%', 'offset': 70}" data-image-src="easy2hire/img/parallax/parallax-corporate-14-2.jpg">
    <div class="container container-lg">
      <div class="row align-items-center">
        <div class="col-md-6 col-lg-5 col-xl-6 text-center pr-5 mb-5 mb-md-0 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="400">
          <img src="easy2hire/img/2_home.png" class="img-fluid" alt="" style="height:320px;max-width:530px;margin-left:-10px;"/>
        </div>
        <div class="col-md-6 col-lg-7 col-xl-6 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="200">
          <span class="font-weight-bold text-color-dark opacity-8 text-4">Easy2hire makes your Operations More Fruitful Than Before with Deep Tracking Capabilities.</span>
          <h2 class="font-weight-bold text-9 mb-4">Enhance Transparency</h2>
          <ul class="list list-icons pb-2 mb-4">
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Track active/closed leads, clients, candidates, jobs, interviews & more</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Accurate forecasting & recovery.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Color-based job segregation for quick heads-up.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Check your team’s attendance.</span></li>
          </ul>
          <a href="/transparent" class="btn btn-primary font-weight-semibold rounded-0 btn-px-5 py-3 text-2">LEARN MORE</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-height-3 section-parallax bg-color-grey-scale-1 border-0 m-0 appear-animation" data-appear-animation="fadeIn" data-plugin-parallax data-plugin-options="{'speed': 1.5, 'parallaxHeight': '100%', 'offset': 70}" data-image-src="easy2hire/img/parallax/parallax-corporate-14-3.jpg">
    <div class="container container-lg">
      <div class="row justify-content-between align-items-center">
        <div class="col-md-7 order-2 order-md-1 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="200">
          <span class="font-weight-bold text-color-dark opacity-8 text-4">See how you are doing as a company by deriving valuable insights about your routine operations with Easy2Hire’s deep data analytics.</span>
          <h2 class="font-weight-bold text-9 mb-4">Data Insights</h2>
          <ul class="list list-icons pb-2 mb-4">
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Check Quality of Leads.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Analyze Current/Past Client Status.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Set & Analyze Job Priority.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Compare Actual vs Aligned Delivery.</span></li>
          </ul>
          <a href="/data_insight" class="btn btn-primary font-weight-semibold rounded-0 btn-px-5 py-3 text-2">LEARN MORE</a>
        </div>
        <div class="col-md-4 text-center text-md-left order-1 order-md-2 mb-5 mb-md-0 mr-lg-5 appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="400">
          <img src="easy2hire/img/3_home.png" class="img-fluid" alt="" style="height:330px;max-width:530px;margin-left:-120px;"/>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-height-3 section-parallax bg-color-light border-0 m-0" data-plugin-parallax data-plugin-options="{'speed': 1.5, 'parallaxHeight': '100%', 'offset': 70}" data-image-src="easy2hire/img/parallax/parallax-corporate-14-2.jpg">
    <div class="container container-lg">
    <div class="row align-items-center">
      <div class="col-md-6 col-lg-5 col-xl-6 text-center pr-5 mb-5 mb-md-0 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="400">
        <img src="easy2hire/img/4_home.png" class="img-fluid" alt="" style="height:320px;max-width:530px;margin-left:-10px;"/>
      </div>
      <div class="col-md-6 col-lg-7 col-xl-6 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="200">
        <span class="font-weight-bold text-color-dark opacity-8 text-4">Stop wasting time on searching for information and start working in just a few minutes with a separate module for every important business activity. 
          Interactive Business Dashboard</span>
          <h2 class="font-weight-bold text-9 mb-4">Dedicated Modules for Easy Information Access</h2>
          <ul class="list list-icons pb-2 mb-4">
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Accurate Business Reports.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">End-to-end Job Openings Management.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Streamlined Interview Management.</span></li>
            <li><i class="fas fa-caret-right top-6"></i> <span class="text-4">Tactical & Automated Candidate Profiling.</span></li>
          </ul>
          <a href="/modules" class="btn btn-primary font-weight-semibold rounded-0 btn-px-5 py-3 text-2">Check out Different Easy2Hire Modules Now</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-height-5 section-background overlay overlay-show overlay-op-7 border-0 m-0 appear-animation" data-appear-animation="fadeIn" style="background-image: url(easy2hire/img/p_logo.jpg); background-size: cover; background-position: center;">
    <div class="container container-lg my-5">
      <div class="row justify-content-center">
        <div class="col-md-10 col-xl-9 text-center">
          <h2 class="font-weight-bold text-color-light text-11 mb-4 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">You Can Be Next by Joining Our Esteemed Clientele</h2>
         <!--  <p class="font-weight-light text-color-light line-height-9 text-4 opacity-7 mb-5 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">More Than 1500+ Agencies Globally have Realized Their True Potential</p> -->
          <a href="/demo_request" class="d-inline-flex align-items-center btn btn-primary font-weight-semibold px-5 btn-py-3 text-3 rounded appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="550">Improve Your Operations with EASY2HIRE NOW<i class="fa fa-arrow-right ml-2 pl-1 text-5"></i></a>
        </div>
      </div>
    </div>
  </section>

  <div class="row mr-5 ml-5">
    <div class="col">
      <div class="row mt-4 mb-3">
        <div class="col text-center">
          <h2 class="font-weight-semibold text-9 mb-3">Clients Love Easy2Hire </h2>
        </div>
      </div>
      <div class="lightbox" data-plugin-options="{'delegate': 'a', 'type': 'image', 'gallery': {'enabled': true}, 'mainClass': 'mfp-with-zoom', 'zoom': {'enabled': false, 'duration': 300}}">
        <div class="owl-carousel owl-theme stage-margin" data-plugin-options="{'items': 6, 'margin': 10, 'loop': true, 'nav': true, 'dots': false, 'stagePadding': 40}">
          <div>
            <a class="img-thumbnail img-thumbnail-no-borders img-thumbnail-hover-icon" href="easy2hire/img/logo1.png">
              <img class="img-fluid" src="easy2hire/img/logo1.png" alt="Project Image">
            </a>
          </div>
          <div>
            <a class="img-thumbnail img-thumbnail-no-borders img-thumbnail-hover-icon" href="easy2hire/img/logo2.png">
              <img class="img-fluid" src="easy2hire/img/logo2.png" alt="Project Image">
            </a>
          </div>
          <div>
            <a class="img-thumbnail img-thumbnail-no-borders img-thumbnail-hover-icon" href="easy2hire/img/logo3.png">
              <img class="img-fluid" src="easy2hire/img/logo3.png" alt="Project Image">
            </a>
          </div>
          <div>
            <a class="img-thumbnail img-thumbnail-no-borders img-thumbnail-hover-icon" href="easy2hire/img/logo4.png">
              <img class="img-fluid" src="easy2hire/img/logo4.png" alt="Project Image">
            </a>
          </div>
          <div>
            <a class="img-thumbnail img-thumbnail-no-borders img-thumbnail-hover-icon" href="easy2hire/img/logo5.png">
              <img class="img-fluid" src="easy2hire/img/logo5.png" alt="Project Image">
            </a>
          </div>
          <div>
            <a class="img-thumbnail img-thumbnail-no-borders img-thumbnail-hover-icon" href="easy2hire/img/logo6.png">
              <img class="img-fluid" src="easy2hire/img/logo6.png" alt="Project Image">
            </a>
          </div>
          <div>
            <a class="img-thumbnail img-thumbnail-no-borders img-thumbnail-hover-icon" href="easy2hire/img/logo7.png">
              <img class="img-fluid" src="easy2hire/img/logo7.png" alt="Project Image">
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <section class="section section-height-3 section-background section-text-dark section-center bg-color-light">
      <div class="row mb-3">
        <div class="col text-center">
          <span class="font-weight-bold text-color-dark opacity-8 text-4">OUR BLOG</span>
          <h2 class="font-weight-semibold text-9 mb-3">Clients Love Easy2Hire </h2>
        </div>
      </div>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="owl-carousel owl-theme nav-bottom rounded-nav dots-dark mb-0" data-plugin-options="{'items': 1, 'loop': false, 'autoHeight': true}">
              <div>
                <div class="col">
                  <div class="testimonial testimonial-style-2 testimonial-with-quotes mb-0">
                    <blockquote>
                      <p>Never before were we more focused towards our goals and added so many clients in a single quarter. Easy2Hire made tracking everything- from clients to candidates, from interviews to accounts really easy to manage. True to its name!</p>
                    </blockquote>
                    <div class="testimonial-author">
                      <p><strong class="text-2 opacity-10">- TrajInfo. India</strong></p>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="col">
                  <div class="testimonial testimonial-style-2 testimonial-with-quotes mb-0">
                    <blockquote>
                      <p>We were an organization solely relying on manual methods for client and candidate management. Easy2Hire helped us step us by improving and digitizing our workflow.</p>
                    </blockquote>
                    <div class="testimonial-author">
                      <p><strong class="text-2 opacity-10">- TrajInfo. India</strong></p>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="col">
                  <div class="testimonial testimonial-style-2 testimonial-with-quotes mb-0">
                    <blockquote>
                      <p>Digital transformation is the buzzword every recruitment agency fears about. Resistance to change is a great challenge. But with Easy2Hire, we didn’t face any difficulty. Its simple interface and useful features reduced workload, enhanced staff engagement and improved everyone’s morale.</p>
                    </blockquote>
                    <div class="testimonial-author">
                      <p><strong class="text-2 opacity-10">- TrajInfo. India</strong></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <section class="bg-color-grey-scale-1">
    <div class="container container-lg py-5 my-5">
      <div class="row mb-3">
        <div class="col-md-9 col-lg-9">
          <div class="overflow-hidden mb-1">
            <h2 class="font-weight-normal text-7 mt-2 mb-0 appear-animation animated maskUp appear-animation-visible" data-appear-animation="maskUp" data-appear-animation-delay="200" style="animation-delay: 200ms;"><strong class="font-weight-extra-bold">Contact</strong> Us</h2>
          </div>
          <div class="overflow-hidden mb-4 pb-3">
            <p class="mb-0 appear-animation animated maskUp appear-animation-visible" data-appear-animation="maskUp" data-appear-animation-delay="400" style="animation-delay: 400ms;">Feel free to ask for details, don't save any questions!</p>
          </div>

            <div class="contact-form-success alert alert-success d-none mt-4" id="sendmessage">
              <strong>Success!</strong> Your message has been sent to us.
            </div>

            <div class="contact-form-error alert alert-danger d-none mt-4" id="errormessage">
              <strong>Error!</strong> There was an error sending your message.
              <span class="mail-error-message text-1 d-block" id="mailErrorMessage"></span>
            </div>
            <form id="contactForm" class="contactForm" action="{{route('contact.us')}}" method="POST" style="animation-delay: 600ms;">
                 {{ csrf_field()}}
            <div class="form-row">
              <div class="form-group col-lg-6">
                <label class="required font-weight-bold text-dark">Full Name</label>
                <input value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" required="" type="text">
              </div>
              <div class="form-group col-lg-6">
                <label class="required font-weight-bold text-dark">Email Address</label>
                <input value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email" required="" type="email">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col">
                <label class="font-weight-bold text-dark">Subject</label>
                <input value="" data-msg-required="Please enter the subject." maxlength="100" class="form-control" name="subject" id="subject" required="" type="text">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col">
                <label class="required font-weight-bold text-dark">Message</label>
                <textarea maxlength="5000" data-msg-required="Please enter your message." rows="8" class="form-control" name="message" id="message" required></textarea>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col">
                <input value="Send Message" class="btn btn-primary btn-modern" data-loading-text="Loading..." type="submit">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

</div>
@stop