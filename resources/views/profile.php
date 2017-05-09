@extends('layouts.app')

@section('content')
<table class="table">
    <tbody>
      <tr>
        <td>Name</td>
        <td>{{$user->full_name}}</td>
        <td>Gender</td>
        <td>{{$user->gender}}</td>
        
      </tr>
    </tbody>
</table>

@endsection
