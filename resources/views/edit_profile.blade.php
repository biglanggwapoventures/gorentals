@extends('layouts.app')

@section('content')
<div class="container">
	<h3>EDIT PROFILE</h3>
	@if(isset($messages))
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

	<form action="/profile" method="POST" enctype="multipart/form-data" style="margin-bottom: 30px">
	{{ csrf_field() }}
	<div class="form-group">
      <label>Username: </label>
      <input type="text" class="form-control" placeholder="user name" value="{{Auth::user()->username}}" name="username">
    </div>
	<div class="form-group">
      <label>First name:</label>
      <input type="text" class="form-control" placeholder="first name" value="{{Auth::user()->firstname}}" name="firstname">
    </div>
    <div class="form-group">
      <label>Last name:</label>
      <input type="text" class="form-control" placeholder="Last name" value="{{Auth::user()->lastname}}" name="lastname">
    </div>
    <div class="form-group">
      <label>Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" value="{{Auth::user()->email}}" name="email">
    </div>
    <div class="form-group">
      <label>Mobile No:</label>
      <input type="text" class="form-control" placeholder="mobile number" value="{{Auth::user()->mobile_number}}" name="mobile_number">
    </div>
    <div class="form-group">
	  <label>Gender</label>
	  <select class="form-control" name="gender">
	    <option value="MALE">Male</option>
	    <option value="FEMALE">Female</option>
	  </select>
	 </div>
    <div class="form-group">
        <div class="controls">
            <label for="">Profile Picture</label>
            <input type="file" name="profile_picture">
        </div>
    </div>       
    <button type="submit" class="btn btn-default">Update</button>
  </form>
</div>
@endsection
