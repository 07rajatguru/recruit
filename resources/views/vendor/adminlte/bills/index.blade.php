@extends('adminlte::page')

@section('title', 'Bills')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{$title}}</h2>
            </div>

            @if($title=="Bills Not Made")
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
<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="jo_table">
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
            <th>Percentage Charged</th>
            <th>Source</th>
            <th>Client Name</th>
            <th>Client Contact Number</th>
            <th>Client Email Id</th>
        </tr>
    </thead>
        <?php $i=0; ?>
        <tbody>
        @foreach($bnm as $key=>$value)
            <tr>
                <td><input type="checkbox" name="id[]" value="{{$value['id']}}"></td>
                <td>
                    @if($title=="Bills Not Made")
                        @if($access || ($user_id==$value['uploaded_by']) )
                            <a class="fa fa-edit" title="Edit" href="{{ route('bnm.edit',$value['id']) }}"></a>
                             @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'bnm','display_name'=>'Bill'])
                            <a class="fa fa-circle" title="show" href="{{ route('bnm.show',$value['id']) }}"></a>

                            @if($value['status']==0)
                            <!-- BM will be generated after date of joining -->
                                @if(date("Y-m-d")>= date("Y-m-d",strtotime($value['date_of_joining'])))
                                  <a class="fa fa-square"  title="Generate BM" href="{{ route('bills.generatebm',$value['id']) }}"></a>
                                @endif
                            @endif

                        @endif
                    @endif

                    @if($title=="Bills Made")
                        @if($access)
                                <a class="fa fa-edit" title="Edit" href="{{ route('bnm.edit',$value['id']) }}"></a>
                        @endif
                    @endif

                   </td>
                   <td>{{ ++$i }}</td>
                   @if($access=='true')
                       <td>{{ $value['user_name'] }}</td>
                   @endif
                   <td>{{ $value['display_name'] }} - {{$value['posting_title']}} , {{ $value['city'] }}</td>
                   <td>{{ $value['cname'] }}</td>
                   <td>{{ $value['date_of_joining'] }}</td>
                   <td>{{ $value['fixed_salary'] }}</td>
                   <td>{{ $value['efforts'] }}</td>
                   <td>{{ $value['candidate_contact_number'] }}</td>
                   <td>{{ $value['job_location'] }}</td>
                   <td>{{ $value['percentage_charged'] }}</td>
                   <td>{{ $value['source'] }}</td>
                   <td>{{ $value['client_name'] }}</td>
                   <td>{{ $value['client_contact_number'] }}</td>
                   <td>{{ $value['client_email_id'] }}</td>

               </tr>
           @endforeach
           </tbody>
       </table>
       <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
                           @stop
                           @section('customscripts')
                               <script type="text/javascript">
                                   $(document).ready(function(){
                                       var table = $('#jo_table').DataTable( {
                                          columnDefs: [  {
                                               'targets': 0,
                                               'searchable':false,
                                               'orderable':false,
                                               'className': 'dt-body-center',
                                               /*'render': function (data, type, full, meta){
                                                   return '<input type="checkbox" name="id[]" value="'
                                                       + $('<div/>').text(data).html() + '">';
                                               }*/
                                           } ],
                                           /*scrollY: "300px",
                                           scrollX:  true,
                                           scrollCollapse: true,
                                           paging:         false,
                                           columnDefs: [
                                               { width: 200, targets: 0 }
                                           ],*/
                                           responsive: true
                                       } );

                                       new jQuery.fn.dataTable.FixedHeader( table );

                                       $('#example-select-all').on('click', function(){
                                           // Check/uncheck all checkboxes in the table
                                           var rows = table.rows({ 'search': 'applied' }).nodes();
                                           $('input[type="checkbox"]', rows).prop('checked', this.checked);

                                       });

                                       // Handle click on checkbox to set state of "Select all" control
                                       $('#jo_table tbody').on('change', 'input[type="checkbox"]', function(){
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

                                       var table = $('#jo_table').DataTable();
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
