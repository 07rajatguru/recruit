@extends('adminlte::page')

@section('title', 'Recovery Listing')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h4><b>Financial Year</b> : {{ $financial_year }}</h4>

            <div class="pull-left">
                <h2>Recovery List ({{ $count or 0}})</h2>
            </div>
            
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('bills.recovery') }}">Back</a> 
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="bill_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Action</th>

                @if($access=='true')
                    <th>Added by</th>
                @endif

                <th>Job Openings</th>
                <th>Candidate Name</th>
                <th>Joining Date</th>
                <th>Fixed Salary</th>
                <th>Efforts</th>
                <th>Candidate <br/>Contact Number</th>
                <th>Job Location</th>

                @permission(('display-recovery'))
                  <th>Percentage Charged</th>
                @endpermission

                <th>Source</th>
                <th>Client Name</th>
                <th>Client Contact Number</th>
                <th>Client Email Id</th>

                @permission(('display-recovery'))
                  <th>Lead Efforts</th>
                @endpermission
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
            @foreach($recovery_list as $key=>$value)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        @if($access == 'true' || ($user_id == $value['uploaded_by']) || ($user_id == $value['account_manager_id']))
                            
                            <a class="fa fa-edit" title="Edit" href="{{route('forecasting.edit',$value['id']) }}"></a>

                            @permission(('cancel-bill'))
                                @if($value['cancel_bill'] == 0)
                                    @include('adminlte::partials.cancelbill', ['data' => $value,'name' => 'forecasting','display_name'=>'Bill','year' => $year])
                                @endif
                            @endpermission

                            @permission(('recovery-delete'))
                                @include('adminlte::partials.deleteModalNew', ['data' => $value,'name' => 'forecasting','display_name'=>'Bill','year' => $year])
                            @endpermission

                            @permission(('send-joining-confirmation'))

                                @if($value['job_confirmation'] == 0 && $value['cancel_bill'] == 0)

                                    @include('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.sendconfirmationmail','class' => 'fa fa-send', 'title' => 'Send Confirmation Mail', 'model_title' => 'Send Confirmation Mail', 'model_body' => 'want to Send Confirmation Mail?','year' => $year])

                                @endif

                                @if($value['job_confirmation'] == 1 && $value['cancel_bill'] == 0)

                                    @include('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.gotconfirmation','class' => 'fa fa-check-circle', 'title' => 'Got Confirmation', 'model_title' => 'Got Confirmation Mail', 'model_body' => 'you Got Confirmation Mail?','year' => $year])
                                    
                                @endif

                                @if($value['job_confirmation'] == 2 && $value['cancel_bill'] == 0)

                                    @include('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.invoicegenerate','class' => 'fa fa-file', 'title' => 'Generate Invoice', 'model_title' => 'Generate Invoice', 'model_body' => 'want to Generate Invoice?','year' => $year])
                                    
                                @endif

                                @if($value['job_confirmation'] == 3 && $value['cancel_bill'] == 0)

                                    @include('adminlte::partials.sendmail', ['data' => $value, 'name' => 'recovery.paymentreceived','class' => 'fa fa-money', 'title' => 'Payment Received', 'model_title' => 'Payment Received', 'model_body' => 'received Payment?','year' => $year])
                                    
                                @endif

                                @if(isset($value['excel_invoice_url']) && $value['excel_invoice_url'] != NULL)
                                    <a class="fa fa-download" title="Download Invoice" href="{{route('invoice.excel',$value['id']) }}"></a>
                                @endif
                            @endpermission

                            @permission(('cancel-bill'))
                                @if($value['cancel_bill'] == 1)
                                    @include('adminlte::partials.relivebill', ['data' => $value,'name' => 'recovery','display_name'=>'Recovery'])
                                @endif
                            @endpermission
                        @endif
                    </td>

                    @if($access == 'true')
                        <td>{{ $value['user_name'] }}</td>
                    @endif

                    <td>{{ $value['company_name'] }},{{ $value['city'] }}</td>
                    <td>{{ $value['cname'] }}</td>
                    <td>{{ $value['date_of_joining'] }}</td>
                    <td>{{ $value['fixed_salary'] }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['efforts'] }}</td>
                    <td>{{ $value['candidate_contact_number'] }}</td>
                    <td>{{ $value['job_location'] }}</td>

                    @permission(('display-recovery'))
                        <td>{{ $value['percentage_charged'] }}</td>
                    @endpermission

                    <td>{{ $value['source'] }}</td>
                    <td>{{ $value['client_name'] }}</td>
                    <td>{{ $value['client_contact_number'] }}</td>
                    <td>{{ $value['client_email_id'] }}</td>

                    @permission(('display-recovery'))
                        <td>{{ $value['lead_efforts'] }}</td>
                    @endpermission
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var table = jQuery('#bill_table').DataTable({

                responsive: true,
                "order" : [5,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]}],
                "pageLength": 25,
                stateSave: true
            });

            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection