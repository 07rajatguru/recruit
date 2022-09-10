@extends('adminlte::page')

@section('title', 'Client')

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
                <h2>{{ $client['name'] }}</h2>
            </div>
           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('client.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header col-md-6"><h3 class="box-title">Basic Information</h3></div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                         <tr>
                            @permission(('display-client'))
                                <th scope="row">Contact Point</th>
                                <td>{{ $client['coordinator_name'] }}</td>
                                <th>Contact Number</th>
                                <td>{{ $client['mobile'] }}</td>
                            @else
                                @permission(('display-account-manager-wise-client'))
                                    <th scope="row">Contact Point</th>
                                    <td>{{ $client['coordinator_name'] }}</td>
                                    <th>Contact Number</th>
                                    <td>{{ $client['mobile'] }}</td>
                                @else
                                    <th scope="row">Contact Point</th>
                                    <td colspan="3">{{ $client['coordinator_name'] }}</td>
                                @endif
                            @endpermission
                        </tr>

                        <tr>
                            @permission(('display-client'))
                                <th>Email</th>
                                <td>{{ $client['mail'] }}</td>
                                <th>Source</th>
                                <td>{{ $client['source'] }}</td>
                            @else
                                @if($client['client_owner'])
                                    <th>Email</th>
                                    <td colspan="3">{{ $client['mail'] }}</td>
                                @endif
                            @endpermission
                        </tr>

                        <tr>
                            <th>Account Manager</th>
                            <td>{{ $client['am_name'] }}</td>
                            <th>Website</th>
                            <td>{{ $client['website'] }}</td>
                        </tr>

                        <tr>
                            @permission(('display-client'))
                                <th>Industry</th>
                                <td>{{ $client['ind_name'] }}</td>
                                <th>GST Number</th>
                                <td>{{ $client['gst_no'] }}</td>
                            @else
                                <th>Industry</th>
                                <td colspan="3">{{ $client['ind_name'] }}</td>
                            @endpermission
                        </tr>
                     
                        <tr>
                            <th>About</th>
                            <td style="white-space: pre-wrap; word-wrap: break-word;">{!! $client['description'] !!} </td>
                            <th> Client Status</th>
                            <td>{{ $client['status'] }}</td>
                        </tr>
                        
                        @permission(('display-client'))
                        <tr>
                            <th>Percentage Charged Below AM Position</th>
                            <td>{{ $client['percentage_charged_below'] }}</td>
                            <th>Percentage Charged Above AM Position</th>
                            <td>{{ $client['percentage_charged_above'] }}</td>
                        </tr>
                        @endpermission

                        <tr>
                            @permission(('display-client-category-in-client-list'))
                                <th>Display Name</th>
                                <td>{{ $client['display_name'] }}</td>
                                <th>Client Category</th>
                                <td>{{ $client['category'] }}</td>
                            @else
                                <th>Display Name</th>
                                <td colspan="3">{{ $client['display_name'] }}</td>
                            @endpermission
                        </tr>

                        <tr>
                            <th>Designation</th>
                            <td>{{ $client['designation'] }}</td>
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
                            <th colspan="2" style="text-align:center;">Address 1</th>
                            <th colspan="2"  style="text-align:center;">Address 2</th>
                        </tr>

                        <tr>
                            <th>Street Address</th>
                            <td>{{ $client['billing_street'] }}</td>
                            <th>Street Address</th>
                            <td>{{ $client['shipping_street'] }}</td>
                        </tr>

                        <tr>
                            <th>City</th>
                            <td>{{ $client['billing_city'] }}</td>
                            <th>City</th>
                            <td>{{ $client['shipping_city'] }}</td>
                        </tr>

                        <tr>
                            <th>State</th>
                            <td>{{ $client['billing_state'] }}</td>
                            <th>State</th>
                            <td>{{ $client['shipping_state'] }}</td>
                        </tr>

                        <tr>
                            <th>Country</th>
                            <td>{{ $client['billing_country'] }}</td>
                            <th>Country</th>
                            <td>{{ $client['shipping_country'] }}</td>
                        </tr>

                        <tr>
                            <th>Code</th>
                            <td>{{ $client['billing_code'] }}</td>
                            <th>Code</th>
                            <td>{{ $client['shipping_code'] }}</td>
                        </tr>
                    </table>
                </div>
             </div>
        </div>
        @permission(('display-client'))
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header  col-md-6 ">
                        <h3 class="box-title">Attachments</h3>
                        &nbsp;&nbsp;
                        @include('adminlte::client.upload', ['data' => $client, 'name' => 'clientattachments','type' => 'show'])
                    </div>

                    <div class="box-header col-md-8"></div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <th>File Name</th>
                            <th>Uploaded by</th>
                            <th>Size</th>
                            <th>Category</th>
                        </tr>
                            @if($client['doc'] != '')
                                @foreach($client['doc'] as $key=>$value)
                                    <tr>
                                        <td>
                                            <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                            &nbsp;
                                            @include('adminlte::partials.confirm', ['data' => $value,'clientid'=> $client['id'], 'name' => 'clientattachments' ,'display_name'=> 'Attachments', 'type' => 'show'])
                                        </td>
                                        <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a></td>
                                        <td>{{ $value['uploaded_by'] }}</td>
                                        <td>{{ $value['size'] }}</td>
                                        <td>{{ $value['category'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        @endpermission

        @if(isset($post) && sizeof($post) > 0)
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                    <div class="box-header  col-md-6 ">
                        <h3 class="box-title">Comments</h3>&nbsp;&nbsp;
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        @include('adminlte::client.showremarkslist',array('post' => $post,'super_admin_userid' => $super_admin_userid, 'client_remarks'=>$client_remarks, 'client_remarks_edit' => $client_remarks_edit))
                    </div>
                </div>
            </div>
        @endif
@endsection