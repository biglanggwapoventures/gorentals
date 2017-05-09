@extends('layouts.app')
@section('content')
    @component('components.breadcrumbs')
        @slot('banner')
        <div class="pag_titl_sec">
            <h1 class="pag_titl"> {{ $unit->property->building_name }} </h1>
            <h4 class="sub_titl">  {{ $unit->property->address }} </h4>
        </div>
        @endslot
        <p class="lnk_pag"><a > view unit</a> </p>
    @endcomponent

    <div class="spacer-60"></div>
    <div class="container">
        <div class="row">
            <!-- Proerty Details Section -->
            <section id="prop_detal" class="col-md-8">
                <div class="row">
                    <div class="panel panel-default">
                       <!-- Proerty Slider Images -->
                        <div class="panel-image">
                            <ul id="prop_slid">
                                @foreach($unit->allPhotos() AS $photo)
                                <li class="unit-slider" style="background-image:url({{ asset("storage/{$photo}") }})">
                                    <!-- <img class="img-responsive" src="{{ asset("storage/{$photo}") }}" alt="Property Slide Image"> -->
                                </li>
                                @endforeach
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">    
                           
                            <h3 class="sec_titl">{{ $unit->property->building_name }} <small></small></h3>
                            

                            <p class="sec_desc">
                                
                               {{ $unit->property->address }} (Permit # {{ $unit->property->permit_number }})

                                <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> {{ $unit->property->getTypeDescription() }}</p>
                                <p class="bedrom"><i class="fa fa-bed"></i>  {{ $unit->bedrooms }} Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-shower"></i> {{ $unit->bathrooms }} Bathrooms</p>
                                <p class="bedrom"><i class="fa fa-paint-brush"></i> {{ $unit->furnishings[$unit->furnishing] }}</p>
                                @if(!$isMyFavorite)
                                    @if(Auth::check() && Auth::user()->id !=  $unit->created_by)
                                        <p class="bedrom"><a href="/addfavorite?unit={{$unit->id}}"><i class="fa fa-star-o"></i> Add to Favorites </a></p>
                                    @elseif(!Auth::check())
                                        <p class="bedrom"><a href="" data-toggle="modal" data-target="#login_box"><i class="fa fa-star-o"></i> Add to Favorites </a></p>
                                    @endif
                                @endif
                            </div>

                               <table class="table" style="margin-top:10px;">
                                    <tr>
                                        <td>Rental Term</td>
                                        <td class="text-right"><strong>{{ $unit->rental_terms === 'LONG' ? 'Long Term' :  'Short Term' }}</strong></td>
                                        
                                    </tr>
                                    @if($unit->rental_terms === 'LONG')
                                            <tr>
                                                <td>Rate</td>
                                                <td class="text-right"><strong>Php {{ number_format($unit->long_term_rate, 2) }}</strong></td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>Daily Rate</td>
                                                <td class="text-right"><strong>Php {{ number_format($unit->short_term_daily_rate, 2) }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Weekly Rate</td>
                                                <td class="text-right"><strong>Php {{ number_format($unit->short_term_weekly_rate, 2) }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Monthly Rate</td>
                                                <td class="text-right"><strong>Php {{ number_format($unit->short_term_monthly_rate, 2) }}</strong></td>
                                            </tr>
                                        @endif
                                        <tr>
                                        <td>Inclusions</td>
                                        <td class="text-right"><strong>{{ $unit->inclusions }}</strong></td>
                                        
                                    </tr>
                                </table>

                                <div class="row">
                                    <div class="tags_sec">
                                        @if(!is_null($unit->amenities))
                                        @foreach($unit->amenities AS $a)
                                            <div class="tags_box">
                                                <a><i class="fa fa-check"></i> {{ $a }}  </a>
                                            </div>
                                        @endforeach     
                                        @endif       

                                    </div>
                                </div>
                            </p>

                        </div>
                    </div>
                </div>
                <!-- /.row -->
                <div class="spacer-30"></div>
                
                <!-- Proerty Map -->

                <div class="row">
                    <div class="titl_sec">
                        <div class="col-lg-12">
                            <h3 class="main_titl text-left">Property Map</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="prop_map unit-map" id="map" style="height:300px">
                           
                        </div>

                    </div>
                </div>
                <!-- /.row -->


            </section>

            <!-- Sidebar Section -->       
                          
            <section id="sidebar" class="col-md-4">
                  <!-- Agent Info -->
                <div class="row">
                    <div class="agen_info">
                        <div class="col-md-12">
                            <div class="panel panel-default">

                                <div class="panel-body">
                                <a><img class="img-circle img-thumbnail img-responsive img-hover center-block" src="{{ asset($unit->property->owner->profile_picture) }}" alt="" style="display:none;"></a>
                                    <div class="row agen_desc">
                                        <h3 class="sec_titl text-center" style="margin-top:10px;">
                                            Appointments                               
                                        </h3>
                                    </div>
                                    <div class="panel_bottom" style="padding:0px">
                                        <div class="list-group">
                                            @foreach($appointments as $appointment) 
                                                <li href="" class="list-group-item">{{$appointment->date}} 
                                                <a class="btn btn-primary btn-xs pull-right" style="margin-left: 10px" href="/appointments/{{$appointment->id}}/delete">Delete</a><div class="pull-right">To: {{$appointment->username}}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="spacer-30"></div>

            </section>

            <div class="spacer-60"></div>

        </div>
    </div>
<div id="notif-box" class="modal fade" style="z-index:10000">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Send a message</h4>
                </div>
                <div class="modal-body">
                    <div class="log_form">
                        <form method="POST" action="{{ url('/send-message', ['partner' => $unit->property->created_by]) }}" class="ajax" data-next="{{ url('/notifications') }}">
                            {{ csrf_field() }}
                            <div class="bs-callout bs-callout-danger hidden"></div>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <textarea  class="form-control" name="message"></textarea>
                                </div>

                                <div class="clearfix"></div>

                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::check() && Auth::user()->login_type === 'ADMIN')
    <div id="approve-unit" class="modal fade" style="z-index:10000">
        <div class="modal-dialog modal-sm">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirm action</h4>
                </div>
                <div class="modal-body">
                    <div class="log_form">
                        <form method="POST" action="{{ route('approve-unit', ['unit' => $unit->id]) }}" class="ajax" data-next="{{ url('/admin') }}">
                            {{ csrf_field() }}
                            <p class="text-center">Are you sure?</p>
                            <button type="submit" class="btn btn-primary" style="margin-bottom:10px;">Yes</button>
                            <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
@push('scripts')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1MUjOwwLeP2Jv5Q8o0nt5RH-oSKY5RUw&callback=initMap"></script>
    <script src="{{ asset('js/jquery.bxslider.min.js') }}"></script>
   <script type="text/javascript">  
    function initMap() {
        var myLatLng = {lat: {{ $unit->property->latitude }}, lng:{{ $unit->property->longitude }} };

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 18,
          center: myLatLng,
           mapTypeId: 'satellite'
        });

        var marker = new google.maps.InfoWindow({
          position: myLatLng,
          map: map,
          content: '<p class="text-center lead"><i class="fa fa-home"></i> {{ $unit->property->building_name }}</p>{{ $unit->property->address }}'
        });
    }

     $(document).ready(function () {
        'use strict';

        $('#prop_slid').bxSlider({
            pagerCustom: '#slid_nav'
        });

    });
   </script>
@endpush