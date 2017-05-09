@extends('layouts.app')

@section('content')
<div class="container" style="margin-bottom: 20px">
    <table class="table">
        <h3>Profile</h3>
        @if(!Auth::user()->profile_picture)
        <img src="images/preview_default_profile.png" style="max-width: 100%; height: 80px; margin-bottom:20px" />
        @else
        <img src="{{ asset("storage/{$img}") }}"  style="max-width: 100%; height: 80px; margin-bottom:20px" />
        @endif
        @if(Auth::user()->login_type == 'PROPERTY_OWNER')
        <a class="btn btn-primary" style="margin-left: 20px">Property Owner</a>
        @else
        <a class="btn btn-primary" style="margin-left: 20px">Tenant</a>
        @endif
        <tbody>
          <tr>
            <td>Name</td>
            <td>{{Auth::user()->fullname()}}</td>
          </tr>
          <tr>
            <td>Gender</td>
            <td>{{Auth::user()->gender}}</td>
          </tr>
          <tr>
            <td>Mobile</td>
            <td>{{Auth::user()->mobile_number}}</td>
          </tr>
        </tbody>
    </table> 
    <a href="/profile/edit" class="btn btn-primary">Edit</a>
    <a href="/profile/changepassword" class="btn btn-primary">Change Password</a>
</div>
@endsection
