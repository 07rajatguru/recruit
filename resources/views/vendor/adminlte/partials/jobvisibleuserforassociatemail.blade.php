<strong>Select Users :</strong>

@foreach($users as $k=>$v)&nbsp;&nbsp; 
	{!! Form::checkbox('user_ids[]', $k,null, array('id'=>'user_ids','size'=>'10','class' => 'users_ids')) !!}
	{!! Form::label ($v) !!}
@endforeach