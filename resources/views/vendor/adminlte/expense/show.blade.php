@extends('adminlte::page')

@section('title', 'Expense Detail')

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
                <h2>Expense Detail</h2>
            </div>

           <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('expense.index') }}">Back</a>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                <div class="box-header col-md-6 ">
                    <h3 class="box-title">Expense Information</h3>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered">
                         <tr>
                            <th scope="row">Date</th>
                            <td>{{ $expense['date'] }}</td>
                            <th>Paid To(Vendor)</th>
                            <td>{{ $expense['name'] }}</td>
                        </tr>
                        <tr>
                            <th>Vendor GST No.</th>
                            <td>{{ $expense['gstno'] }}</td>
                            <th>Vendor PAN No.</th>
                            <td>{{ $expense['panno'] }}</td>
                        </tr>
                         <tr>
                            <th>Billing Amount</th>
                            <td>{{ $expense['amount'] }}</td>
                            <th>GST</th>
                            <td>{{ $expense['gst'] }}</td>
                        </tr>
                        <tr>
                            <th>CGST</th>
                            <td>{{ $expense['cgst'] }}</td>
                            <th>SGST</th>
                            <td>{{ $expense['sgst'] }}</td>
                        </tr>
                        <tr>
                            <th>IGST</th>
                            <td>{{ $expense['igst'] }}</td>
                            <th>Total Amount</th>
                            <td>{{ $expense['total_bill'] }}</td>
                        </tr>
                        <tr>
                            <th>Paid Amount</th>
                            <td>{{ $expense['paidamt'] }}</td>
                            <th>Expense Head</th>
                            <td>{{ $expense['head'] }}</td>
                        </tr>
                        <tr>
                            <th>TDS(%)</th>
                            <td>{{ $expense['tds'] }}</td>
                            <th>Deducted TDS</th>
                            <td>{{ $expense['tds_deduct'] }}</td>
                        </tr>
                        <tr>
                            <th>TDS Date</th>
                            <td>{{ $expense['tds_date'] }}</td>
                            <th>Reference Number</th>
                            <td>{{ $expense['reference'] }}</td>
                        </tr>
                        <tr>
                            <th>Payment Mode</th>
                            <td>{{ $expense['pmode'] }}</td>
                            <th>Payment Type</th>
                            <td>{{ $expense['ptype'] }}</td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td>{{ $expense['remark'] }}</td>
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
                    @include('adminlte::expense.upload', ['data' => $expense, 'name' => 'expenseattachments'])
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
                        @if(sizeof($expense['doc'])>0)
                            @foreach($expense['doc'] as $key=>$value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $expense['id'], 'name' => 'expenseattachments' ,'display_name'=> 'Attachments'])
                                    </td>

                                    <td>
                                    <a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}
                                    </a>
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