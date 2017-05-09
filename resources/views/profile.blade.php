@extends('layouts.app')

@section('content')
<div class="container" style="margin-bottom: 20px">
    <table class="table">
        <h3>Profile</h3>
        @if(is_null($user->profile_picture))
        <img src="/images/preview_default_profile.png" style="max-width: 100%; height: 80px;" />
        @else
        <img src="{{asset("storage/{$user->profile_picture}")}}" style="max-width: 100%; height: 80px" />
        @endif
        @if($user->login_type == 'PROPERTY_OWNER')
        <a class="btn btn-primary" style="margin-left: 20px">Property Owner</a>
        @else
        <a class="btn btn-primary" style="margin-left: 20px">Tenant</a>
        @endif
        <tbody>
          <tr>
            <td>Name</td>
            <td>{{$user->fullname()}}</td>
          </tr>
          <tr>
            <td>Gender</td>
            <td>{{$user->gender}}</td>
          </tr>
          <tr>
            <td>Mobile</td>
            <td>{{$user->mobile_number}}</td>
          </tr>
        </tbody>
    </table>    
</div>
@endsection
