@extends('adminlte::page')

@section('title', 'Bills')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                @if($cancel_bill == 0)
                  <h2>{{$title}} ({{$count}})</h2>
                @else
                  @if($cancel_bnm == 1)
                    <h2>{{$title}} ({{$count}})</h2>
                  @else
                    <h2>{{$title}} ({{$count}})</h2>
                  @endif
                @endif
            </div>

            @if($title=="Forecasting")
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('bills.create') }}"> Create New Bill</a>
                </div>
            @endif
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <div>
        {{--{!! Form::open(array('route' => 'jobopen.store','files' => true,'method'=>'POST', 'id' => 'jobsForm')) !!}
            <button type="button" class="btn btn-primary" onclick="downloadExcel();">Download Excel</button>
        {!! Form::close() !!}--}}

    </div>
<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="bill_table">
    <thead>
        <tr>
            <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
            <th>Action</th>
            <th>No</th>
            @if($access=='true')
                <th>Added by</th>
            @endif
            <th>Job Openings</th>
            <th>Candidate Name</th>
            <th>Joining Date</th>
            <th>Fixed Salary</th>
            <th>Efforts</th>
            <th>Candidate Contact Number</th>
            <th>Job Location</th>
            @if($isSuperAdmin || $isAccountant)
              <th>Percentage Charged</th>
            @endif
            <th>Source</th>
            <th>Client Name</th>
            <th>Client Contact Number</th>
            <th>Client Email Id</th>
            @if($isSuperAdmin || $isAccountant)
              <th>Lead Efforts</th>
            @endif
        </tr>
    </thead>
       {{-- php $i=0;
        <tbody>
        @foreach($bnm as $key=>$value)
        php 
            if ($value['job_confirmation'] == '1') {
              $color = '#00B0F0';
            }
            else if ($value['job_confirmation'] == '2') {
              $color = '#FFA500';
            }
            else if ($value['job_confirmation'] == '3') {
              $color = '#FFC0CB';
            }
            else if ($value['job_confirmation'] == '4') {
              $color = '#32CD32';
            }
            else {
              $color = '';
            }
        
            <tr>
                <td><input type="checkbox" name="id[]" value="{{$value['id']}}"></td>
                <td>
                    @if($title=="Forecasting")
                        @if($access || ($user_id==$value['uploaded_by']) )
                            <a class="fa fa-edit" title="Edit" href="{{ route('forecasting.edit',$value['id']) }}"></a>
                            <a class="fa fa-circle" title="show" href="{{ route('forecasting.show',$value['id']) }}"></a>
                        <a class="fa fa-close" title="Cancel Forecasting" href="{{ route('forecasting.cancel',$value['id']) }}"></a>
                          @if($isSuperAdmin)
                             @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill'])
                          @endif
                           @if($value['cancel_bill']==0)
                            @include('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill'])
                           @endif

                            @if($value['status']==0)
                              @if($value['cancel_bill']!=1)
                            <!-- BM will be generated after date of joining -->
                                @if(date("Y-m-d")>= date("Y-m-d",strtotime($value['date_of_joining'])))
                                  <a class="fa fa-square"  title="Generate Recovery" href="{{ route('bills.generaterecovery',$value['id']) }}"></a>
                                @endif
                              @endif  
                            @endif

                        @endif
                        @if($isSuperAdmin || $isAccountant)
                          @if($value['cancel_bill']==1)
                            @include('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Forcasting'])
                          @endif
                        @endif
                    @endif

                    @if($title=="Recovery")
                        @if($access || ($user_id==$value['uploaded_by']))
                                <a class="fa fa-edit" title="Edit" href="{{ route('forecasting.edit',$value['id']) }}"></a>
                                @if($isSuperAdmin)
                                  @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill'])
                                @endif
                                @if($value['cancel_bill']==0)
                                  @include('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'forecasting','display_name'=>'Bill'])
                                @endif
                                @if($isSuperAdmin || $isAccountant)
                                  @if($value['job_confirmation'] == 0 && $value['cancel_bill']==0)
                                    @include('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.sendconfirmationmail', 'class' => 'fa fa-send', 'title' => 'Send Confirmation Mail', 'model_title' => 'Send Confirmation Mail', 'model_body' => 'want to Send Confirmation Mail?'])
                                  @elseif($value['job_confirmation'] == 1 && $value['cancel_bill']==0)
                                    @include('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.gotconfirmation', 'class' => 'fa fa-check-circle', 'title' => 'Got Confirmation', 'model_title' => 'Got Confirmation Mail', 'model_body' => 'you Got Confirmation Mail?'])
                                  @elseif($value['job_confirmation'] == 2 && $value['cancel_bill']==0)
                                    @include('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.invoicegenerate', 'class' => 'fa fa-file', 'title' => 'Generate Invoice', 'model_title' => 'Generate Invoice', 'model_body' => 'want to Generate Invoice?'])
                                  @elseif($value['job_confirmation'] == 3 && $value['cancel_bill']==0)
                                    @include('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.paymentreceived', 'class' => 'fa fa-money', 'title' => 'Payment Received', 'model_title' => 'Payment Received', 'model_body' => 'received Payment?'])
                                  @endif
                                  @if(isset($value['invoice_url']) && $value['invoice_url'] != NULL)
                                    <a target="_blank" href="{{$value['invoice_url']}}"><i  class="fa fa-fw fa-download"></i></a>
                                  @endif
                                @endif
                        @endif
                        @if($isSuperAdmin || $isAccountant)
                          @if($value['cancel_bill']==1)
                            @include('adminlte::partials.relivebill', ['data' => $value, 'name' => 'recovery','display_name'=>'Recovery'])
                          @endif
                        @endif
                    @endif

                   </td>
                   <td>{{ ++$i }}</td>
                   @if($access=='true')
                       <td>{{ $value['user_name'] }}</td>
                   @endif
                   <td style="white-space: pre-wrap; word-wrap: break-word;background-color: {{ $color }};">{{ $value['display_name'] }} - {{$value['posting_title']}} , {{ $value['city'] }}</td>
                   <td>{{ $value['cname'] }}</td>
                   <td data-th="Lastrun" data-order="{{$value['date_of_joining_ts']}}">{{ $value['date_of_joining'] }}</td>
                   <td>{{ $value['fixed_salary'] }}</td>
                   <td>{{ $value['efforts'] }}</td>
                   <td>{{ $value['candidate_contact_number'] }}</td>
                   <td>{{ $value['job_location'] }}</td>
                   @if($isSuperAdmin || $isAccountant)
                      <td>{{ $value['percentage_charged'] }}</td>
                   @endif
                   <td>{{ $value['source'] }}</td>
                   <td>{{ $value['client_name'] }}</td>
                   <td>{{ $value['client_contact_number'] }}</td>
                   <td>{{ $value['client_email_id'] }}</td>
                   @if($isSuperAdmin || $isAccountant)
                      <td>{{ $value['lead_efforts'] }}</td>
                   @endif
               </tr>
           @endforeach
           </tbody>--}}
       </table>
       <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
       <input type="hidden" name="title" id="title" value="{{ $title }}">
@stop
@section('customscripts')
   <script type="text/javascript">
       $(document).ready(function(){

           /*var table = $('#bill_table').DataTable( {
              columnDefs: [  {
                   'targets': 0,
                   'searchable':false,
                   'orderable':false,
                   'className': 'dt-body-center',
                   'render': function (data, type, full, meta){
                       return '<input type="checkbox" name="id[]" value="'
                           + $('<div/>').text(data).html() + '">';
                   }
               } ],

               scrollY: "300px",
               scrollX:  true,
               scrollCollapse: true,
               paging:         false,
               columnDefs: [
                   { width: 200, targets: 0 }
               ],
               responsive: true,
               stateSave: true,
               "pageLength": 100
           } );

           new jQuery.fn.dataTable.FixedHeader( table );*/
            var title = $("#title").val();
            if (title == 'Forecasting' || title == 'Recovery') {
              $("#bill_table").dataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [2,'desc'],
                "columnDefs": [ {orderable: false, targets: [0]},
                                {orderable: false, targets: [1]},
                            ],
                "ajax" : {
                    'url' : 'bills/all',
                    'type' : 'get',
                    "data": {title:title},
                    error: function(){

                    }
                },
                responsive: true,
                "pageLength": 25,
                "pagingType": "full_numbers",
                stateSave : true,
                "fnRowCallback": function( Row, Data ) {
                    if ( Data[17] == "1" ){
                        $('td:eq(5)', Row).css('background-color', '#00B0F0');
                    }
                    else if ( Data[17] == "2" ){
                        $('td:eq(5)', Row).css('background-color', '#FFA500');
                    }
                    else if ( Data[17] == "3" ){
                        $('td:eq(5)', Row).css('background-color', '#FFC0CB');
                    }
                    else if ( Data[17] == "4" ){
                        $('td:eq(5)', Row).css('background-color', '#32CD32');
                    }
                  }
              });
            }
            else if (title == 'Cancel Forecasting' || title == 'Cancel Recovery') {
              $("#bill_table").dataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [2,'desc'],
                "columnDefs": [ {orderable: false, targets: [0]},
                                {orderable: false, targets: [1]},
                            ],
                "ajax" : {
                    'url' : '/bills/cancel/all',
                    'type' : 'get',
                    "data": {title:title},
                    error: function(){

                    }
                },
                responsive: true,
                "pageLength": 100,
                "pagingType": "full_numbers",
                stateSave : true,
                "fnRowCallback": function( Row, Data ) {
                    if ( Data[17] == "1" ){
                        $('td:eq(5)', Row).css('background-color', '#00B0F0');
                    }
                    else if ( Data[17] == "2" ){
                        $('td:eq(5)', Row).css('background-color', '#FFA500');
                    }
                    else if ( Data[17] == "3" ){
                        $('td:eq(5)', Row).css('background-color', '#FFC0CB');
                    }
                    else if ( Data[17] == "4" ){
                        $('td:eq(5)', Row).css('background-color', '#32CD32');
                    }
                  }
              });
            }

           $('#example-select-all').on('click', function(){
               // Check/uncheck all checkboxes in the table
               var rows = table.rows({ 'search': 'applied' }).nodes();
               $('input[type="checkbox"]', rows).prop('checked', this.checked);

           });

           // Handle click on checkbox to set state of "Select all" control
           $('#bill_table tbody').on('change', 'input[type="checkbox"]', function(){
               // If checkbox is not checked
               if(!this.checked){
                   var el = $('#example-select-all').get(0);
                   // If "Select all" control is checked and has 'indeterminate' property
                   if(el && el.checked && ('indeterminate' in el)){
                       // Set visual state of "Select all" control
                       // as 'indeterminate'
                       el.indeterminate = true;
                   }
               }
           });

       });

       function downloadExcel() {

           var table = $('#bill_table').DataTable();
           var ids = new Array();
           // Iterate over all checkboxes in the table
           table.$('input[type="checkbox"]').each(function () {
               // If checkbox is checked
               if (this.checked) {
                   ids.push(this.value);
               }
           });

           var token = $('input[name="csrf_token"]').val();
           $.ajax({
               type: 'POST',
               url: 'bills/downloadexcel',
               data: { ids:ids ,'_token':token},
               success: function(res)
               {

               }
           });

       }

   </script>
@endsection
