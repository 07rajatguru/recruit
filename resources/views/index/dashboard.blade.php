@extends('index.headerfooter',['PageName' => 'dashboard'])

@section('body')
<div class="inner_banner">
   <div class="container">
      <h1 class="title text-8 font-weight-extra-bold">Dashboard</h1>
   </div>
</div>
<div class="middle_sec dashboard">
   <div  class="container-fluid">
      <div class="row">
         <div class="dashboard_left col-md-6">
            <div class="dashboard_cont_inner">
               <h2>Gauge your Business Growth Potential with Figures That Matter!</h2>
               <p>For a recruitment agency, nothing is more important than the regular flow of new clients. With Easy2Hire, check the efficacy of your business development efforts by identifying monthly new account acquisitions.</p>
               <p>The dashboard displays the total number of clients added in the current month and number of active jobs- the figures that impact your short & long-term business growth & profitability.</p>
            </div>
         </div>
         <div class="dashboard_right col-md-6">
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">12</h4>
                  <p>Client’s Added in Current Month</p>
               </div>
            </div>
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">10</h4>
                  <p>Active Jobs This Month</p>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="dashboard_left col-md-6 order-md-12">
            <div class="dashboard_cont_inner">
               <h2>Streamline Interview Scheduling Process</h2>
               <p>Stay informed about today’s & tomorrow’s interview, right from the dashboard. Check interview status, contact candidates and be efficient at the whole interview scheduling process.</p>
               <p>Never again scrounge for interview details manually in a crowded mailbox or in spreadsheets or document folders.</p>
            </div>
         </div>
         <div class="dashboard_right col-md-6">
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">11</h4>
                  <p>Follow-up with Candidates Instantly</p>
               </div>
            </div>
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">5</h4>
                  <p>Speed-up Interview Alignment Process</p>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="dashboard_left col-md-6">
            <div class="dashboard_cont_inner">
               <h2>Evaluate your Team’s Productivity in Minutes</h2>
               <p>The informative dashboard helps you manage end-to-end recruitment operations while at the same time understand how productive you were as a recruitment agency. Compare open job positions against profiles associated and find out if your team has achieved the productivity goals, month-on-month.</p>
            </div>
         </div>
         <div class="dashboard_right col-md-6">
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">11</h4>
                  <p>CVs Associated with Current Job Openings</p>
               </div>
            </div>
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">15</h4>
                  <p>Monthly Interviews Aligned</p>
               </div>
            </div>
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">7</h4>
                  <p>Monthly Candidate Joining</p>
               </div>
            </div>
         </div>
      </div>
      <div class="row align-self-center">
      <div class="dashboard_left col-md-6 order-md-12">
            <div class="dashboard_cont_inner">
               <h2>Never Miss an Important Update or Task with To-Do lists & Real-time Notifications</h2>
               <p>In the recruitment industry, every second count. With Easy2Hire, make sure everyone stays on the same page with company-wide ToDo lists & notification reminders. Assign to-do tasks to yourself or to a team member from dashboard. Be notified in real-time with notifications about new job openings, leads & more.</p>
            </div>
         </div>
         <div class="dashboard_right col-md-6">
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">101</h4>
                  <p>Professional To-do list</p>
               </div>
            </div>
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">100</h4>
                  <p>Role Assignments</p>
               </div>
            </div>
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">20</h4>
                  <p>General Recruitment Notifications</p>
               </div>
            </div>
            <div class="dashboard_counter">
               <div class="v_center">
                  <h4 class="counter">10</h4>
                  <p>Notification Reminders for Work-to-do</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop