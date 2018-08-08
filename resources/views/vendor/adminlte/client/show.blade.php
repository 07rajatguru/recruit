@extends('adminlte::page')

@section('title', 'Client Detail')

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

                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Basic Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                         <tr>
                            <th scope="row"  >HR/Coordinator Name</th>
                            <td>{{ $client['coordinator_name'] }}</td>

                             @if($isSuperAdmin || $isAdmin)
                                 <th>Contact Number</th>
                                 <td>{{ $client['mobile'] }}</td>
                             @elseif($client['client_owner'])
                                 <th>Contact Number</th>
                                 <td colspan="3">{{ $client['mobile'] }}</td>
                             @else
                                 <th>Contact Number</th>
                                 <td colspan="3"></td>
                             @endif
                        </tr>

                        <tr>
                            @if($isSuperAdmin || $isAdmin)
                                <th>Email</th>
                                <td>{{ $client['mail'] }}</td>
                            @elseif($client['client_owner'])
                                <th>Email</th>
                                <td colspan="3">{{ $client['mail'] }}</td>
                            @else
                                <th>Email</th>
                                <td colspan="3"></td>
                            @endif
                            @if($isSuperAdmin || $isAdmin)
                                <th>Source</th>
                                <td>{{ $client['source'] }}</td>
                            @endif
                        </tr>

                        <tr>
                            <th>Account Manager</th>
                            <td>{{ $client['am_name'] }}</td>
                            <th>Website</th>
                            <td>{{ $client['website'] }}</td>
                        </tr>

                        <tr>
                            @if($isSuperAdmin || $isAdmin)
                                <th>Industry</th>
                                <td>{{ $client['ind_name'] }}</td>
                            @else
                                <th>Industry</th>
                                <td colspan="3">{{ $client['ind_name'] }}</td>
                            @endif
                            @if($isSuperAdmin || $isAdmin)
                                <th>GST Number</th>
                                <td>{{ $client['gst_no'] }}</td>
                            @endif
                        </tr>
                        @if($isSuperAdmin || $isAdmin)
                            <tr>
                                <th>TDS</th>
                                <td>{{ $client['tds'] }}</td>
                                <th>TAN</th>
                                <td>{{ $client['tan'] }}</td>
                            </tr>
                        @endif

                        <tr>
                            <th>About</th>
                            <td colspan="3">{!! $client['description'] !!}</td>
                        </tr>
                         @if($isSuperAdmin || $isAdmin)
                        <tr>
                            <th>Percentage Charged Below AM Position</th>
                            <td>{{ $client['percentage_charged_below'] }}</td>
                            <th>Percentage Charged Above AM Position</th>
                            <td>{{ $client['percentage_charged_above'] }}</td>
                        </tr>
                        @endif
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
    @if($isSuperAdmin || $isAdmin)
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                <div class="box-header  col-md-6 ">
                    <h3 class="box-title">Attachments</h3>
                    &nbsp;&nbsp;
                    @include('adminlte::client.upload', ['data' => $client, 'name' => 'clientattachments'])
                </div>

                <div class="box-header  col-md-8 ">

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                    <tr>
                        <th></th>
                        <th>File Name</th>
                        <th>Uploaded by</th>
                        <th>Size</th>
                        <th>Category</th>
                    </tr>
                        @if(sizeof($client['doc'])>0)
                            @foreach($client['doc'] as $key=>$value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'clientid'=> $client['id'], 'name' => 'clientattachments' ,'display_name'=> 'Attachments'])
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
    @endif
    </div>
@endsection