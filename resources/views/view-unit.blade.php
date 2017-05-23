@extends('layouts.app')

@section('content')
    @component('components.breadcrumbs')
        @slot('banner')
        <div class="pag_titl_sec">
            <h1 class="pag_titl"> {{ $unit->property->building_name }} </h1>
            <h4 class="sub_titl"> @if($unit->property->extension) {{$unit->property->extension}} - @endif {{ $unit->property->address }} </h4>
        </div>
        @endslot
        <p class="lnk_pag"><a > view unit</a> </p>
    @endcomponent

    <div class="spacer-60"></div>
    <div class="container">
        <div class="row">
            @if (Session::has('message'))
               <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
            <!-- Proerty Details Section -->
            <section id="prop_detal" class="col-md-8">
                <div class="row">
                    <div class="panel panel-default">
                       <!-- Proerty Slider Images -->
                        <div class="panel-image">
                            <ul id="prop_slid">
                                @foreach($unit->allPhotos() AS $photo)
                                <!-- <li class="unit-slider"> -->
                                <li class="unit-slider" style="background-image:url({{ asset("storage/{$photo}") }});">
                                    <!-- <img class="img-responsive" src="{{ asset("storage/{$photo}") }}" alt="Property Slide Image"> -->
                                </li>
                                @endforeach
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">    
                           
                            <h3 class="sec_titl">{{ $unit->property->building_name }} <small></small></h3>
                            

                            <p class="sec_desc">
                                
                               @if($unit->property->extension) {{$unit->property->extension}} - @endif {{ $unit->property->address }} (Permit # {{ $unit->property->permit_number }})

                                <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> {{ $unit->property->getTypeDescription() }}</p>
                                @if($unit->property->property_type == 'DORMITORY')
                                    <p class="bedrom"><i class="fa fa-bed"></i>  {{ $unit->bedrooms }} Capacity</p>
                                @else
                                    <p class="bedrom"><i class="fa fa-bed"></i>  {{ $unit->bedrooms }} Bedrooms</p>
                                @endif
                                
                                <p class="bedrom"><i class="fa fa-shower"></i> {{ $unit->bathrooms }} Bathrooms</p>
                                <p class="bedrom"><i class="fa fa-paint-brush"></i> {{ $unit->furnishings[$unit->furnishing] }}</p>
                                @if($unit->property->property_type == 'DORMITORY')
                                <p class="bedrom">Gender: {{$unit->property->gender}}</p>
                                @endif
                                @if(!$isMyFavorite)
                                    @if(Auth::check() && Auth::user()->id != $unit->created_by && !Auth::user()->isAdmin() && Auth::user()->login_type != 'PROPERTY_OWNER')
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
                                        <td>Policy</td>
                                        <td class="text-right"><strong><a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#policy-modal"><i class="fa fa-check"></i> View Policy</a></strong></td>
                                    </tr>
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
                        <div class="prop_map unit-map" id="maptwo" style="height:300px">
                           
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
                                            <a href="agents_single.html"> {{ $unit->property->owner->fullname() }} </a>                                   
                                        </h3>
                                    </div>
                                    <div class="panel_bottom text-center" style="padding:0px">
                                        <ul class="list-inline" style="margin-top:10px;">
                                            @if(Auth::check() && Auth::user()->login_type === 'ADMIN')
                                                @if(is_null($unit->approved_at))<li><a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#approve-unit"><i class="fa fa-fw fa-check"></i> Approve Unit</a></li>@endif
                                            @elseif(Auth::check() && Auth::user()->id != $unit->created_by)
                                                 <li><a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#notif-box"><i class="fa fa-fw fa-envelope"></i> Send Message</a></li>
                                            @elseif(!Auth::check())
                                                <li><a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#login_box"><i class="fa fa-fw fa-envelope"></i> Send Message</a></li>
                                            @endif
                                            <li><a href="/users/{{$unit->property->owner->id}}" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-user"></i> View Owner Profile</a></li>
                                            @if(!$alreadyAppointment)
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#appointment_box" style="margin-top: 5px">Set an appointment</button>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="spacer-30"></div>
                
                <div class="row side-bar-map-nearby">
                    <div class="col-sm-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div id="map_canvas" style="height: 300px;"></div>
                                <br>
                                <div id="keywordField">
                                    <label>Key word:</label>
                                    <input id="keyword" type="text" class="form-control"/>
                                </div>
                                <div id="controls"> 
                                    <span id="typeLabel"><label>Type:</label> </span>
                                    <select id="type" class="form-control">
                                        <option value="" selected="selected">All</option>
                                        <option value="bar">Bars</option>
                                        <option value="cafe">Cafe</option>
                                        <option value="clothing_store">Clothing store</option>
                                        <option value="museum">Museums</option>
                                        <option value="restaurant">Restaurants</option>
                                        <option value="university">University</option>
                                        <option value="church">Church</option>
                                        <option value="police">Police Station</option>
                                        <option value="shopping_mall">Shopping Mall</option>

                                    </select> 
                                    <span id="rankByLabel"><label>Rank by:</label></span>
                                    <select id="rankBy" class="form-control">
                                        <option value="prominence">Prominence</option>
                                        <option value="distance" selected="selected">Distance</option>
                                    </select>
                                </div>
                                <br>
                                <div id="listing">
                                    <label>Nearby Places:</label>
                                    <table id="resultsTable" class="col-lg-12">
                                        <tbody id="results"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    @include('property-owner.modal-set-appointment')
    <div class="modal fade" id="policy-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Policies</h4>
            </div>
            <div class="modal-body">
                @foreach($unit->property->policies AS $policy)
                    <div style="margin-bottom:10px;">
                        <p style="border-bottom: 1px dashed #ddd;"><strong>{{ $policy->name }}</strong></p>
                        <span class="text-info">{{ $policy->description }}</span>
                    </div>
                @endforeach
                <div style="margin-bottom:10px;">
                    <p style="border-bottom: 1px dashed #ddd;"><strong>Others:</strong></p>
                    <span class="text-info">{{ $unit->property->policy }}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHfHTt-Y8HqG9rC3i5SMGjCYJyjAlQN08&libraries=places,geometry"></script>
<!-- 
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1MUjOwwLeP2Jv5Q8o0nt5RH-oSKY5RUw&callback=initMap"></script>
    <script src="{{ asset('js/distance_mtrx.js') }}"></script>

 -->
    <script src="{{ asset('js/jquery.bxslider.min.js') }}"></script>

   <script type="text/javascript">  
     $(document).ready(function () {
        'use strict';

        $('#datetimepicker1').datetimepicker();
        $('#prop_slid').bxSlider({
            pagerCustom: '#slid_nav'
        });

        $('#set-app').submit(function(e){
            e.preventDefault();

            var errorField =  $(this).find('.error');
            errorField.addClass('hidden');

            $.post($(this).attr('action'), $(this).serialize())
                .done(function(res) {
                    if(res.result) window.location.reload();
                    else{
                       errorField.removeClass('hidden').text(res.message);
                    }
                })
        })
    });



var map, places, iw;
var markers = [];
var searchTimeout;
var centerMarker;
var autocomplete;
var hostnameRegexp = new RegExp('^https?://.+?/');
var myLatlng;

  function initialize() {

    var myLatLng = {lat: {{ $unit->property->latitude }}, lng:{{ $unit->property->longitude }} };
    myLatlng = new google.maps.LatLng({{ $unit->property->latitude }}, {{ $unit->property->longitude }});
      
      var myOptionsTwo = {
          zoom: 18,
          center: myLatlng,
          mapTypeId: 'satellite'
      }
      map = new google.maps.Map(document.getElementById('maptwo'), myOptionsTwo);

      var marker = new google.maps.InfoWindow({
          position: myLatlng,
          map: map,
          content: '<p class="text-center lead"><i class="fa fa-home"></i> {{ $unit->property->building_name }}</p>{{ $unit->property->address }}'
        });

      var myOptions = {
          zoom: 16,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);


      places = new google.maps.places.PlacesService(map);
      google.maps.event.addListener(map, 'tilesloaded', tilesLoaded);

       var circleOptions = {strokeColor: '#FF0000', strokeOpacity: 0.8, strokeWeight: 2, fillColor: '#FF0000', fillOpacity: 0.35, map: map, center: myLatlng, radius: 150, };
  circle = new google.maps.Circle(circleOptions);

      document.getElementById('keyword').onkeyup = function (e) {
          if (!e) var e = window.event;
          if (e.keyCode != 13) return;
          document.getElementById('keyword').blur();
          search(document.getElementById('keyword').value);
      }

      var typeSelect = document.getElementById('type');
      typeSelect.onchange = function () {
          search();
      };

      var rankBySelect = document.getElementById('rankBy');
      rankBySelect.onchange = function () {
          search();
      };
  }

  function tilesLoaded() {
      search();
      google.maps.event.clearListeners(map, 'tilesloaded');
      google.maps.event.addListener(map, 'zoom_changed', searchIfRankByProminence);
      google.maps.event.addListener(map, 'dragend', search);
  }

  function searchIfRankByProminence() {
      if (document.getElementById('rankBy').value == 'prominence') {
          search();
      }
  }

  function search() {
      clearResults();
      clearMarkers();

      if (searchTimeout) {
          window.clearTimeout(searchTimeout);
      }
      searchTimeout = window.setTimeout(reallyDoSearch, 500);
  }

  function reallyDoSearch() {
      var type = document.getElementById('type').value;
      var keyword = document.getElementById('keyword').value;
      var rankBy = document.getElementById('rankBy').value;

      var search = {};

      if (keyword) {
          search.keyword = keyword;
      }

      if (type != 'establishment') {
          search.types = [type];
      }

      if (rankBy == 'distance' && (search.types || search.keyword)) {
          search.rankBy = google.maps.places.RankBy.DISTANCE;
          search.location = map.getCenter();
          centerMarker = new google.maps.Marker({
              position: search.location,
              animation: google.maps.Animation.DROP,
              map: map
          });
      } else {
          search.bounds = map.getBounds();
      }

      places.search(search, function (results, status) {
          if (status == google.maps.places.PlacesServiceStatus.OK) {
              for (var i = 0; i < results.length; i++) {

                  var distance = Math.round(google.maps.geometry.spherical.computeDistanceBetween(myLatlng, results[i].geometry.location));

                  console.log(distance, results[i]);

                  markers.push(new google.maps.Marker({
                      position: results[i].geometry.location,
                      animation: google.maps.Animation.DROP
                  }));
                  google.maps.event.addListener(markers[i], 'click', getDetails(results[i], i));
                  window.setTimeout(dropMarker(i), i * 100);
                  addResult(results[i], i, distance);
              }
          }
      });
  }

  function clearMarkers() {
      for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(null);
      }
      markers = [];
      if (centerMarker) {
          centerMarker.setMap(null);
      }
  }

  function dropMarker(i) {
      return function () {
          if (markers[i]) {
              markers[i].setMap(map);
          }
      }
  }

  function addResult(result, i, distance) {
      var results = document.getElementById('results');
      var tr = document.createElement('tr');
      tr.style.backgroundColor = (i % 2 == 0 ? '#F0F0F0' : '#FFFFFF');
      tr.onclick = function () {
          google.maps.event.trigger(markers[i], 'click');
      };


      var nameTd = document.createElement('td');

      //var name = document.createTextNode(result.name + ' - <span>' + distance + 'm </span>');

      var name = document.createRange().createContextualFragment(result.name + ' <span class="pull-right">' + distance + 'm </span>');
      //range.insertNode(name);

      nameTd.appendChild(name);

      tr.appendChild(nameTd);
      results.appendChild(tr);
  }

  function clearResults() {
      var results = document.getElementById('results');
      while (results.childNodes[0]) {
          results.removeChild(results.childNodes[0]);
      }
  }

  function getDetails(result, i) {
      return function () {
          places.getDetails({
              reference: result.reference
          }, showInfoWindow(i));
      }
  }

  function showInfoWindow(i) {
      return function (place, status) {
          if (iw) {
              iw.close();
              iw = null;
          }

          if (status == google.maps.places.PlacesServiceStatus.OK) {
              iw = new google.maps.InfoWindow({
                  content: getIWContent(place)
              });
              iw.open(map, markers[i]);
          }
      }
  }

  function getIWContent(place) {
      var content = '';
      content += '<table>';
      content += '<tr class="iw_table_row">';
      content += '<td style="text-align: right"><img class="hotelIcon" src="' + place.icon + '"/></td>';
      content += '<td><b><a href="' + place.url + '">' + place.name + '</a></b></td></tr>';
      content += '<tr class="iw_table_row"><td class="iw_attribute_name">Address:</td><td>' + place.vicinity + '</td></tr>';
      if (place.formatted_phone_number) {
          content += '<tr class="iw_table_row"><td class="iw_attribute_name">Telephone:</td><td>' + place.formatted_phone_number + '</td></tr>';
      }
      if (place.rating) {
          var ratingHtml = '';
          for (var i = 0; i < 5; i++) {
              if (place.rating < (i + 0.5)) {
                  ratingHtml += '&#10025;';
              } else {
                  ratingHtml += '&#10029;';
              }
          }
          content += '<tr class="iw_table_row"><td class="iw_attribute_name">Rating:</td><td><span id="rating">' + ratingHtml + '</span></td></tr>';
      }
      if (place.website) {
          var fullUrl = place.website;
          var website = hostnameRegexp.exec(place.website);
          if (website == null) {
              website = 'http://' + place.website + '/';
              fullUrl = website;
          }
          content += '<tr class="iw_table_row"><td class="iw_attribute_name">Website:</td><td><a href="' + fullUrl + '">' + website + '</a></td></tr>';
      }
      content += '</table>';
      return content;
  }

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

  initialize();
   </script>
@endpush