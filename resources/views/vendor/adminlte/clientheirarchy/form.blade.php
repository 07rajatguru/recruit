@section('customs_css')
<style>
    .error
    {
        color:#f56954 !important;
    }
</style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Client Heirarchy</h2>
            @else
                <h2>Add New Client Heirarchy</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{url()->previous()}}"> Back</a>
        </div>
    </div>
</div>

@if($action == 'edit')
    {!! Form::model($client_heirarchy,['method' => 'PATCH', 'files' => true, 'route' => ['clientheirarchy.update', $client_heirarchy['id']],'id'=>'client_heirarchy_form', 'autocomplete' => 'off']) !!}
@else
    {!! Form::open(['files' => true, 'route' => 'clientheirarchy.store','id'=>'client_heirarchy_form', 'autocomplete' => 'off']) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <strong>Name: <span class = "required_fields">*</span> </strong>
                        {!! Form::text('name', null, array('id'=>'name','class' => 'form-control','placeholder' => 'Name','tabindex' => '1' )) !!}
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group {{ $errors->has('order') ? 'has-error' : '' }}">
                        <strong>Order: <span class = "required_fields">*</span> </strong>
                        {!! Form::text('order', null, array('id'=>'order','class' => 'form-control','placeholder' => 'Order','tabindex' => '1' )) !!}
                        @if ($errors->has('order'))
                            <span class="help-block">
                                <strong>{{ $errors->first('order') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}