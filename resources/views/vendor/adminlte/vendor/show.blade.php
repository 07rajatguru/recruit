@extends('adminlte::page')

@section('title', 'Vendor Detail')

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
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ $vendor['name'] }} - {{ $vendor['contact_point'] }}</h1>
            </div>
           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('vendor.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Designation</th>
                            <td>{{ $vendor['designation'] }}</td>
                            <th>Category</th>
                            <td>{{ $vendor['organization_type'] }}</td>
                            
                        </tr>
                        <tr>
                            <th>Mobile Number</th>
                            <td>{{ $vendor['mobile'] }}</td>
                            <th>Landline Number</th>
                            <td>{{ $vendor['landline'] }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $vendor['mail'] }}</td>
                            <th>Website</th>
                            <td>{{ $vendor['website'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Address Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Vendor Address</th>
                            <td>{{ $vendor['vendor_address'] }}</td>
                            <th>Pincode</th>
                            <td>{{ $vendor['pincode'] }}</td>
                            <th>State</th>
                            <td>{{ $vendor['state_nm'] }}</td>
                        </tr>
                    </table>
                 </div>
             </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header  col-md-6 ">
                    <h3 class="box-title">Bank Details</h3>
                </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Bank Name</th>
                            <td>{{ $vendor['bank_name'] }}</td>
                            <th>Bank Address</th>
                            <td>{{ $vendor['bank_address'] }}</td>
                        </tr>
                        <tr>
                            <th>Account Number</th>
                            <td>{{ $vendor['acc_no'] }}</td>
                            <th>IFSC Code</th>
                            <td>{{ $vendor['ifsc_code'] }}</td>   
                        </tr>
                        <tr>
                            <th>Type Of Account</th>
                            <td>{{ $vendor['acc_type'] }}</td>
                            <th>MICR No</th>
                            <td>{{ $vendor['nicr_no'] }}</td>   
                        </tr>
                    </table>
                 </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header  col-md-6 ">
                    <h3 class="box-title">Statutory Request</h3>
                </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>GST No</th>
                            <td>{{ $vendor['gst_in'] }}</td>
                            <th>GST Charge</th>
                            <td>{{ $vendor['gst_charge'] }}</td>
                            <th>PAN Number</th>
                            <td>{{ $vendor['pan_no'] }}</td>
                        </tr>
                    </table>
                 </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header  col-md-6 ">
                    <h3 class="box-title">Attachments</h3>
                    &nbsp;&nbsp;
                    @include('adminlte::vendor.upload', ['data' => $vendor, 'name' => 'vendorattachments'])
                </div>

                <div class="box-header  col-md-8 ">

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                    <tr>
                        <th></th>
                        <th>File Name</th>
                        <th>Size</th>
                    </tr>
                        @if(sizeof($vendor['doc'])>0)
                            @foreach($vendor['doc'] as $key=>$value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $vendor['id'], 'name' => 'vendorattachments' ,'display_name'=> 'Attachments'])
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a>
                                    </td>
                                    <td>{{ $value['size'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection