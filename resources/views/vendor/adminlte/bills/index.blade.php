@extends('adminlte::page')

@section('title', 'Bills')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
              <h2>{{$title}} <span id="count">({{ $count or 0 }})</span></h2>
            </div>

            @if($title=="Forecasting")
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('bills.create') }}"> Create New Bill</a>
                </div>
            @endif
        </div>
    </div>

    @if($title == "Recovery" || $title == "Cancel Recovery")
      @permission(('display-recovery-by-loggedin-user'))
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                  <a id="jc_sent_href" href="" title="Joining Confirmation Sent" style="text-decoration: none;color: black;">
                      <div style="width:max-content;height:40px;background-color:#00B0F0;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px;cursor: pointer;"><b><span id="jc_sent">({{ $jc_sent }})</span></b></div>
                  </a>
              </div>
              <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="got_con_href" href="" title="Got Confirmation" style="text-decoration: none;color: black;">
                    <div style="width:max-content;height:40px;background-color:#FFA500;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px;cursor: pointer;"><b><span id="got_con">({{ $got_con }})</span></b></div>
                </a>
              </div>
              <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="invoice_gen_href" href="" title="Invoice Generated" style="text-decoration: none;color: black;">
                    <div style="width:max-content;height:40px;background-color:#FFC0CB;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px;cursor: pointer;"><b><span id="invoice_gen">({{ $invoice_gen }})</span></b></div>
                </a>
              </div>
              <div class="col-xs-3 col-sm-3 col-md-3" style="width: max-content;">
                <a id="pymnt_rcv_href" href="" title="Payment Received" style="text-decoration: none;color: black;">
                    <div style="width:max-content;height:40px;background-color:#32CD32;padding:9px 25px;font-weight: 600;border-radius: 22px;margin:0 0 10px;cursor: pointer;"><b><span id="pymnt_rcv">({{ $pymnt_rcv }})</span></b></div>
                </a>
              </div>
            </div>
          </div><br/>
      @endpermission
    @endif

    @if($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($title == "Recovery" || $title == "Cancel Forecasting" || $title == "Cancel Recovery")
      <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="box-body col-xs-12 col-sm-6 col-md-3">
              <div class="form-group">
                  <strong>Select Financial Year:</strong>

                  @if($selected_year = Session::get('selected_year'))
                    {{Form::select('year',$year_array, $selected_year, array('id'=>'year','class'=> 'form-control'))}}
                  @else
                    {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
                  @endif
              </div>
          </div>

          <div class="box-body col-xs-12 col-sm-3 col-md-2">
              <div class="form-group" style="margin-top: 19px;">
                  {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
              </div>
          </div>
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

            @permission(('display-forecasting'))
              <th>Percentage Charged</th>
            @endpermission

            <th>Source</th>
            <th>Client Name</th>
            <th>Client Contact Number</th>
            <th>Client Email Id</th>

            @permission(('display-forecasting'))
              <th>Lead Efforts</th>
            @endpermission

        </tr>
      </thead>
    </table>
    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
    <input type="hidden" name="title" id="title" value="{{ $title }}">
@stop

@section('customscripts')
   <script type="text/javascript">
       $(document).ready(function() {

            var title = $("#title").val();
            var year = $("#year").val();
            var numCols = $('#bill_table thead th').length;

            if (title == 'Forecasting' || title == 'Recovery') {

              $("#bill_table").dataTable({

                'bProcessing' : true,
                'serverSide' : true,
                "order" : [6,'desc'],
                "columnDefs": [ {orderable: false, targets: [0]},{orderable: false, targets: [1]}],
                "ajax" : {
                    'url' : 'bills/all',
                    'type' : 'get',
                    "data": {year:year,title:title},
                    error: function() {
                    }
                },
                initComplete:function( settings, json) {

                    var count = json.recordsTotal;
                    $("#count").html("(" + count + ")");

                    var jc_sent = json.bills['jc_sent'];
                    var got_con = json.bills['got_con'];
                    var invoice_gen = json.bills['invoice_gen'];
                    var pymnt_rcv = json.bills['pymnt_rcv'];

                    if(typeof(jc_sent)!="undefined") {
                      $("#jc_sent").html("(" + jc_sent + ")");
                    }
                    else {
                      $("#jc_sent").html("(" + 0 + ")");
                    }

                    if(typeof(got_con)!="undefined") {
                      $("#got_con").html("(" + got_con + ")");
                    }
                    else {
                      $("#got_con").html("(" + 0 + ")");
                    }

                    if(typeof(invoice_gen)!="undefined") {
                      $("#invoice_gen").html("(" + invoice_gen + ")");
                    }
                    else {
                      $("#invoice_gen").html("(" + 0 + ")");
                    }

                    if(typeof(pymnt_rcv)!="undefined") {
                      $("#pymnt_rcv").html("(" + pymnt_rcv + ")");
                    }
                    else {
                      $("#pymnt_rcv").html("(" + 0 + ")");
                    }

                    $("#jc_sent_href").attr("href", '/recovery/1/'+year);
                    $("#got_con_href").attr("href", '/recovery/2/'+year);
                    $("#invoice_gen_href").attr("href", '/recovery/3/'+year);
                    $("#pymnt_rcv_href").attr("href", '/recovery/4/'+year);
                },
                responsive: true,
                "pageLength": 25,
                "pagingType": "full_numbers",
                stateSave : true,
                "fnRowCallback": function( Row, Data ) {

                    if(numCols == 17) {

                      if ( Data[17] == "1" ) {
                          $('td:eq(5)', Row).css('background-color', '#00B0F0');
                      }
                      else if ( Data[17] == "2" ) {
                          $('td:eq(5)', Row).css('background-color', '#FFA500');
                      }
                      else if ( Data[17] == "3" ) {
                          $('td:eq(5)', Row).css('background-color', '#FFC0CB');
                      }
                      else if ( Data[17] == "4" ) {
                          $('td:eq(5)', Row).css('background-color', '#32CD32');
                      }
                    }
                    else {

                      if ( Data[14] == "1" ) {
                          $('td:eq(4)', Row).css('background-color', '#00B0F0');
                      }
                      else if ( Data[14] == "2" ) {
                          $('td:eq(4)', Row).css('background-color', '#FFA500');
                      }
                      else if ( Data[14] == "3" ) {
                          $('td:eq(4)', Row).css('background-color', '#FFC0CB');
                      }
                      else if ( Data[14] == "4" ) {
                          $('td:eq(4)', Row).css('background-color', '#32CD32');
                      }
                    }
                  }
              });
            }

            else if (title == 'Cancel Forecasting' || title == 'Cancel Recovery') {

              $("#bill_table").dataTable({

                'bProcessing' : true,
                'serverSide' : true,
                "order" : [6,'desc'],
                "columnDefs": [ {orderable: false, targets: [0]},{orderable: false, targets: [1]}],
                "ajax" : {
                    'url' : '/bills/cancel/all',
                    'type' : 'get',
                    "data": {year:year,title:title},
                    error: function() {
                    }
                },
                initComplete:function( settings, json) {

                    var count = json.recordsTotal;
                    $("#count").html("(" + count + ")");

                    var jc_sent = json.bills['jc_sent'];
                    var got_con = json.bills['got_con'];
                    var invoice_gen = json.bills['invoice_gen'];
                    var pymnt_rcv = json.bills['pymnt_rcv'];

                    if(typeof(jc_sent)!="undefined") {
                      $("#jc_sent").html("(" + jc_sent + ")");
                    }
                    else {
                      $("#jc_sent").html("(" + 0 + ")");
                    }

                    if(typeof(got_con)!="undefined") {
                      $("#got_con").html("(" + got_con + ")");
                    }
                    else {
                      $("#got_con").html("(" + 0 + ")");
                    }

                    if(typeof(invoice_gen)!="undefined") {
                      $("#invoice_gen").html("(" + invoice_gen + ")");
                    }
                    else {
                      $("#invoice_gen").html("(" + 0 + ")");
                    }

                    if(typeof(pymnt_rcv)!="undefined") {
                      $("#pymnt_rcv").html("(" + pymnt_rcv + ")");
                    }
                    else {
                      $("#pymnt_rcv").html("(" + 0 + ")");
                    }

                    $("#jc_sent_href").attr("href", '/recovery/1/'+year);
                    $("#got_con_href").attr("href", '/recovery/2/'+year);
                    $("#invoice_gen_href").attr("href", '/recovery/3/'+year);
                    $("#pymnt_rcv_href").attr("href", '/recovery/4/'+year);
                },
                responsive: true,
                "pageLength": 100,
                "pagingType": "full_numbers",
                stateSave : true,
                "fnRowCallback": function( Row, Data ) {

                    if(numCols == 17) {

                      if ( Data[17] == "1" ) {
                          $('td:eq(5)', Row).css('background-color', '#00B0F0');
                      }
                      else if ( Data[17] == "2" ) {
                          $('td:eq(5)', Row).css('background-color', '#FFA500');
                      }
                      else if ( Data[17] == "3" ) {
                          $('td:eq(5)', Row).css('background-color', '#FFC0CB');
                      }
                      else if ( Data[17] == "4" ) {
                          $('td:eq(5)', Row).css('background-color', '#32CD32');
                      }
                    }
                    else {

                      if ( Data[14] == "1" ) {
                          $('td:eq(4)', Row).css('background-color', '#00B0F0');
                      }
                      else if ( Data[14] == "2" ) {
                          $('td:eq(4)', Row).css('background-color', '#FFA500');
                      }
                      else if ( Data[14] == "3" ) {
                          $('td:eq(4)', Row).css('background-color', '#FFC0CB');
                      }
                      else if ( Data[14] == "4" ) {
                          $('td:eq(4)', Row).css('background-color', '#32CD32');
                      }
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
               {}
           });
       }

       function select_data() {

          $("#bill_table").dataTable().fnDestroy();

          var year = $("#year").val();
          var title = $("#title").val();
          var numCols = $('#bill_table thead th').length;

          if (title == 'Forecasting' || title == 'Recovery') {

            $("#bill_table").dataTable({

              'bProcessing' : true,
              'serverSide' : true,
              'order' : [6,'desc'],
              "columnDefs": [ {orderable: false, targets: [0]},{orderable: false, targets: [1]}],
              "ajax" : {
                  'url' : 'bills/all',
                  data : {year:year,title:title},
                  'type' : 'get',
                  error: function() {
                  },
              },
              initComplete:function( settings, json) {

                var count = json.recordsTotal;
                $("#count").html("(" + count + ")");

                var jc_sent = json.bills['jc_sent'];
                var got_con = json.bills['got_con'];
                var invoice_gen = json.bills['invoice_gen'];
                var pymnt_rcv = json.bills['pymnt_rcv'];

                if(typeof(jc_sent)!="undefined") {
                  $("#jc_sent").html("(" + jc_sent + ")");
                }
                else {
                  $("#jc_sent").html("(" + 0 + ")");
                }

                if(typeof(got_con)!="undefined") {
                  $("#got_con").html("(" + got_con + ")");
                }
                else {
                  $("#got_con").html("(" + 0 + ")");
                }

                if(typeof(invoice_gen)!="undefined") {
                  $("#invoice_gen").html("(" + invoice_gen + ")");
                }
                else {
                  $("#invoice_gen").html("(" + 0 + ")");
                }

                if(typeof(pymnt_rcv)!="undefined") {
                  $("#pymnt_rcv").html("(" + pymnt_rcv + ")");
                }
                else {
                  $("#pymnt_rcv").html("(" + 0 + ")");
                }

                $("#jc_sent_href").attr("href", '/recovery/1/'+year);
                $("#got_con_href").attr("href", '/recovery/2/'+year);
                $("#invoice_gen_href").attr("href", '/recovery/3/'+year);
                $("#pymnt_rcv_href").attr("href", '/recovery/4/'+year);

              },
              responsive: true,
              "pageLength": 25,
              "pagingType": "full_numbers",
              stateSave : true,
              "fnRowCallback": function( Row, Data ) {

                  if(numCols == 17) {

                    if ( Data[17] == "1" ) {
                      $('td:eq(5)', Row).css('background-color', '#00B0F0');
                    }
                    else if ( Data[17] == "2" ) {
                      $('td:eq(5)', Row).css('background-color', '#FFA500');
                    }
                    else if ( Data[17] == "3" ) {
                      $('td:eq(5)', Row).css('background-color', '#FFC0CB');
                    }
                    else if ( Data[17] == "4" ) {
                      $('td:eq(5)', Row).css('background-color', '#32CD32');
                    }
                  }

                  else {

                    if ( Data[14] == "1" ) {
                      $('td:eq(4)', Row).css('background-color', '#00B0F0');
                    }
                    else if ( Data[14] == "2" ) {
                      $('td:eq(4)', Row).css('background-color', '#FFA500');
                    }
                    else if ( Data[14] == "3" ) {
                      $('td:eq(4)', Row).css('background-color', '#FFC0CB');
                    }
                    else if ( Data[14] == "4" ) {
                      $('td:eq(4)', Row).css('background-color', '#32CD32');
                    }
                  }
                }
            });
          }

          else if (title == 'Cancel Forecasting' || title == 'Cancel Recovery') {

            $("#bill_table").dataTable().fnDestroy();

            $("#bill_table").dataTable({

                'bProcessing' : true,
                'serverSide' : true,
                "order" : [6,'desc'],
                "columnDefs": [ {orderable: false, targets: [0]},{orderable: false, targets: [1]}],
                "ajax" : {
                    'url' : '/bills/cancel/all',
                    data : {year:year,title:title},
                    'type' : 'get',
                    error: function() {
                    }
                },
                initComplete:function( settings, json) {

                  var count = json.recordsTotal;
                  $("#count").html("(" + count + ")");

                  var jc_sent = json.bills['jc_sent'];
                  var got_con = json.bills['got_con'];
                  var invoice_gen = json.bills['invoice_gen'];
                  var pymnt_rcv = json.bills['pymnt_rcv'];

                  if(typeof(jc_sent)!="undefined") {
                    $("#jc_sent").html("(" + jc_sent + ")");
                  }
                  else {
                    $("#jc_sent").html("(" + 0 + ")");
                  }

                  if(typeof(got_con)!="undefined") {
                    $("#got_con").html("(" + got_con + ")");
                  }
                  else {
                    $("#got_con").html("(" + 0 + ")");
                  }

                  if(typeof(invoice_gen)!="undefined") {
                    $("#invoice_gen").html("(" + invoice_gen + ")");
                  }
                  else {
                    $("#invoice_gen").html("(" + 0 + ")");
                  }

                  if(typeof(pymnt_rcv)!="undefined") {
                    $("#pymnt_rcv").html("(" + pymnt_rcv + ")");
                  }
                  else {
                    $("#pymnt_rcv").html("(" + 0 + ")");
                  }

                  $("#jc_sent_href").attr("href", '/recovery/1/'+year);
                  $("#got_con_href").attr("href", '/recovery/2/'+year);
                  $("#invoice_gen_href").attr("href", '/recovery/3/'+year);
                  $("#pymnt_rcv_href").attr("href", '/recovery/4/'+year);

                },

                responsive: true,
                "pageLength": 100,
                "pagingType": "full_numbers",
                stateSave : true,
                "fnRowCallback": function( Row, Data ) {

                    if(numCols == 17) {

                      if ( Data[17] == "1" ) {
                          $('td:eq(5)', Row).css('background-color', '#00B0F0');
                      }
                      else if ( Data[17] == "2" ) {
                          $('td:eq(5)', Row).css('background-color', '#FFA500');
                      }
                      else if ( Data[17] == "3" ) {
                          $('td:eq(5)', Row).css('background-color', '#FFC0CB');
                      }
                      else if ( Data[17] == "4" ) {
                          $('td:eq(5)', Row).css('background-color', '#32CD32');
                      }
                    }
                    else {

                      if ( Data[14] == "1" ) {
                          $('td:eq(4)', Row).css('background-color', '#00B0F0');
                      }
                      else if ( Data[14] == "2" ) {
                          $('td:eq(4)', Row).css('background-color', '#FFA500');
                      }
                      else if ( Data[14] == "3" ) {
                          $('td:eq(4)', Row).css('background-color', '#FFC0CB');
                      }
                      else if ( Data[14] == "4" ) {
                          $('td:eq(4)', Row).css('background-color', '#32CD32');
                      }
                    }
                  }
              });
          }
        }
   </script>
@endsection