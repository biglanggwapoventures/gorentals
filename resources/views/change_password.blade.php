@extends('layouts.app')

@section('content')
<div class="container">
	<h3>CHANGE PASSWORD</h3>
	@if($messages)
	<div class="bs-callout bs-callout-danger">
		<ul class="list-unstyled">
			@foreach($messages as $message)
			<li>{{$message}}</li>
			@endforeach
		</ul>
	</div>
	@endif
	@if ($errors->any())
      {{ implode('', $errors->all('<div>:message</div>')) }}
  @endif

	<form action="/profile/changepassword" method="POST" style="margin-bottom: 30px">
  	{{ csrf_field() }}
    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
  	<div class="form-group">
        <label>Old Password: </label>
        <input type="password" class="form-control" placeholder="old password" name="old_password">
    </div>
    <div class="form-group">
        <label>New Password: </label>
        <input type="password" class="form-control" placeholder="new password" name="password">
    </div>
    <div class="form-group">
        <label>Repeat Password: </label>
        <input type="password" class="form-control" placeholder="repeat password" name="rpassword">
    </div>

    <button type="submit" class="btn btn-default">Update</button>
  </form>
</div>
@endsection
