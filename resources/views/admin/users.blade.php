@extends('layouts.app')

@section('content')
    @component('components.breadcrumbs')
        <p class="lnk_pag"><a> view tenants </a> </p>
    @endcomponent

    <div class="spacer-30"></div>
    <div class="container">
      <div class="row">
          <div class="col-sm-12">
            <section id="feat_propty">
                <div class="container">
                        <div class="row">
                            <div class="titl_sec">
                                <div class="col-xs-6">

                                    <h3 class="main_titl text-left">
                               USER TYPE:  {{ $user_type }} 
                            </h3>

                                </div>
                                <div class="clearfix"></div>
                            </div>

                            @if(empty($users))
                                 <div class="col-md-12">
                                    <div class="bs-callout bs-callout-danger" >
                                        No tenants to show
                                    </div>
                                </div>
                            

                            @else

                                <div class="row">

                                    <div class="col-sm-12">
                                        <form class="form-inline" action="{{url('admin/users')}}">
                                           <!--<div class="row">-->
                                               <input type="hidden" name="type" value="{{ $type }}">
                                               <div class="form-group">
                                                    <label class="sr-only">Name</label>
                                                    <input type="text" class="form-control" name="name" placeholder="Name">
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                    <input name="gender"  value="MALE" type="radio"> Male
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label> 
                                                    <input type="radio" name="gender"  value="FEMALE"> Female
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label> 
                                                    <input type="radio" name="gender"  value=""> All gender
                                                    </label>
                                                </div>
                                           <!--</div>-->
                                            <button type="submit"  class="btn btn-primary">Search</button>
                                        </form>
                                        <table  data-toggle="table" data-show-print="true" class="table" style="margin-top:10px;">
                                            <thead>
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Username</th>
                                                    <th>Email</th>
                                                    <th>Gender</th>
                                                    <th>Mobile Number</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($users AS $row)
                                                    <tr>
                                                        <td>{{$row->firstname}}</td>
                                                        <td>{{$row->lastname}}</td>
                                                        <td>{{$row->username}}</td>
                                                        <td>{{$row->email}}</td>
                                                        <td>{{$row->gender}}</td>
                                                        <td>{{$row->mobile_number}}</td>
                                                        <td>
                                                            @if($row->status == 'ENABLE')
                                                                <a href="/admin/users{{$url_params}}user={{$row->id}}&enable=true" class="btn btn-sm btn-primary">Enable</a>
                                                                <a href="/admin/users{{$url_params}}user={{$row->id}}&enable=false" class="btn btn-sm btn-default">Disable</a>
                                                            @else
                                                                <a href="/admin/users{{$url_params}}user={{$row->id}}&enable=true" class="btn btn-sm btn-default">Enable</a>
                                                                <a href="/admin/users{{$url_params}}user={{$row->id}}&enable=false" class="btn btn-sm btn-primary">Disable</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                 

                            @endif

                           

                        </div>
                        <!-- /.row -->
                </div>
                     <!-- /.container -->
            </section>
          </div>
      </div>
            
    </div>
        <div class="spacer-30"></div>
@endsection